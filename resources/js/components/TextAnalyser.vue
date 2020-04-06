<template>
    <div class="mb-5" data-ga-category="Feedback" v-bind:data-ga-label="preSetAssignment.assignment.code">
        <div class="row" v-if="errors && errors.length">
            <div class="col-md-12">
                <div  class="col-md-12 alert alert-danger" role="alert">
                    <ul>
                        <li v-for="error in errors">{{error.message}}</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- start content -->
            <div id="content" class="col-md-12">
                <div class="subheader shadow">
                    <div class="subheader-title"><h4 v-if="preSetAssignment">{{preSetAssignment.name}}</h4></div>

                    <div class="subheader-message">
                        <div v-if="draftUpdate.message!=''">{{draftUpdate.message}}</div>
                        <div class="small" v-if="auto!=''">{{auto}}</div>
                    </div>

                    <div class="subheader-action" v-if="preSetAssignment">
                        <!-- Auto feedback: <input type="checkbox" v-model="autofeedback" v-on:change="updateAutoFeedback()"> -->
                        <!-- <div class="btn-group pull-right" role="group" aria-label="Actions"> -->
                            <a target="_blank" class="btn btn-primary" v-if="preSetAssignment" v-bind:href="'/analyse/' + preSetAssignment.slug + '/pdf'" data-ga-action="download">Download PDF</a>
                            <!-- <button type="button" class="btn brand-btn-outline-secondary btn-sm" v-on:click="storeAnalysedDrafts('manual')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button> -->
                        <!-- </div> -->
                    </div>
                </div>

                <div class="feedback">
                    <div class="feedback-col" id="original">
                        <p class="alert alert-success px-3">AcaWriter works fastest with short texts, so if you're only working on a specific section, don't paste in the whole document. It still processes long texts, but it may take a few minutes to get your feedback to you.</p>

                        <div id="editor">
                            <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                            <vue-editor v-model="editorContent" :editorToolbar="customToolbar" placeholder="Place your text here..."></vue-editor>
                        </div>
                    </div>

                    <div class="feedback-action">
                        <button type="button" class="btn btn-primary" v-on:click="fetchFeedback('manual')">Get Feedback <i class="fa fa-angle-right"></i></button>
                    </div>

                    <!-- Reflective feedback -->
                    <div class="feedback-col" id="parsed">
                        <p class="alert alert-warning px-3">Computers don’t read writing like humans. So, if you’re sure your writing’s good, it's fine to disagree with AcaWriter's feedback, just like you’d ignore a poor grammar suggestion.</p>

                        <div v-if="this.attributes.isResearch">
                            <research-result></research-result>
                        </div>

                        <div v-else-if="this.attributes.grammar == 'reflective'">
                            <reflective-result></reflective-result>
                        </div>

                        <div v-else-if="this.attributes.grammar == 'analytical'">
                            <analytic-result></analytic-result>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    /**
     commented out - license needed
     import VueFroala from 'vue-froala-wysiwyg';
     Vue.use(VueFroala);
     *
     */
    const EventBus = new Vue();
    import { VueEditor } from 'vue2-editor';
    import moment from 'moment';
    import store from '../store';
    import { mapState, mapActions, mapGetters} from 'vuex';
    import  Reflective from './analyser/Reflective.vue';
    import  Analytic from './analyser/Analytic.vue';
    import  Research from './analyser/Research.vue';

    export default {
        components: {
            VueEditor,
            reflectiveResult: Reflective,
            analyticResult: Analytic,
            researchResult: Research
        },
        name: 'editor',
        props:['document', 'userActivity'],
        store,
        data () {
            return {
                editorContent: '',
                loading: 0,
                tap: [],
                errors: [],
                counter: 0,
                tempIds: [],
                auto: '',
                autosave: false,
                autofeedback: false,
                btnFeedback: false,
                splitText: [],
                quickTags: '',
                customToolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ],
                cssSpec: {
                    inline :['link2me'],
                    // AI/2019-06-25: Removing affect analysis
                    // iconic :['context', 'challenge', 'change', 'metrics', 'affect'],
                    // inText :['affect', 'epistemic','modall']
                    iconic :['context', 'challenge', 'change', 'metrics'],
                    inText :['epistemic','modall']
                },
                analytic_xlator: [
                    {'metrics': 'metrics'},
                    {'emph': 'E'},
                    {'vis': 'T'},
                    {'contrast': 'C'},
                    {'contribution': 'S'},
                    {'nostat': 'N'},
                    {'tempstat': 'B'},
                    {'attitude': 'P'},
                ],
                initFeedback: false,
                intervalId: 0,
                editorStore: null
            }
        },
        mounted () {
            if(this.initFeedback) {
                //this.fetchFeedback();
            }
             this.autoStore();
        },
        created() {
            this.auto = '';
            //setInterval(this.storeAnalysedDrafts, 900000);
           // setInterval(this.quickCheck, 300000);
            //this.autoStore();
        },
        computed: {
            reflective: function() {
                return this.attributes.feedbackOpt == 'reflective' ? 'display:inline': '';
            },
            analytic: function() {
                return this.attributes.feedbackOpt == 'analytical' ? 'display:inline': '';
            },
            ...mapGetters({
                feedback: 'currentFeedback',
                processing: 'loadingStatus'
            }),
            preSetAssignment: function() {
                if(this.document) {
                    return JSON.parse(this.document);
                } else {
                    return false;
                }
            },
            attributes: function() {
                if(this.preSetAssignment) {
                    if(this.preSetAssignment.draft) {
                        this.editorContent = this.preSetAssignment.draft.text_input;
                        let data = {'savedFeed':JSON.parse(this.preSetAssignment.draft.raw_response)};
                        this.$store.dispatch('PRELOAD_FEEDBACK',data);
                        this.initFeedback = false;
                    } else if(this.preSetAssignment.textDraft) {
                        this.editorContent = this.preSetAssignment.textDraft.text_input;
                        this.initFeedback = false;
                    }
                    let feature = this.preSetAssignment.feature[0];
                    return {
                        feedbackOpt:feature.grammar.toLowerCase() == 'analytical' ? 'a_01': 'r_01',
                        grammar: feature.grammar.toLocaleLowerCase(),
                        isResearch: feature.id === 10 || feature.id === 5,
                        feature: feature.id,
                        storeDraftJobRef: Math.random().toString(36).substring(7),
                        initFeedback: this.initFeedback
                    };
                } else {
                   return {
                        feedbackOpt:'a_01',
                        grammar: 'analytical',
                        isResearch: false,
                        feature: 0,
                        storeDraftJobRef: Math.random().toString(36).substring(7),
                        initFeedback: this.initFeedback
                   };
                }
            },
            rulesClasses: function() {
                let rules = [];
                rules = this.feedback.rules.map(function(rule,idx){
                    return rule.css.map(function(cl){
                        return cl;
                    });
                });
                let classes = [].concat(...rules);
                return classes;
            },
            draftUpdate: function() {
                let upd = {};
                upd.message ='';
                var s = this;;
                if(this.userActivity && this.preSetAssignment){
                    this.btnFeedback = false;
                    this.userActivity.forEach(function(activity){
                        if(activity.data) {
                            if(activity.data.type==='Draft' && activity.data.ref === s.attributes.storeDraftJobRef) {
                                upd.message = activity.data.msg;
                                s.auto = moment().format('DD/MM/YYYY hh:mma');
                            }
                        }
                    });
                }
                return upd;
            }
        },
        watch :{
            'editorContent': function(val, oldVal) {
                //this.editorStore = val;
            }
        },
        methods: {
            computeText: function(nv, ov) {
                var changedText='';
                var self = this;
                // var newTap = [];
                var feedbackQueue=[];
                if(nv.length===0) return;
                nv.forEach(function(item, idx) {
                    if(typeof ov[idx]!=='undefined') {
                        if(ov[idx].str!= item) {
                            //str exits but str changed
                            changedText = item;
                            let a = idx;
                            let data = {
                                'send': {'txt': item, 'action': 'quick', 'extra': self.attributes},
                                'idx': idx,
                                'act': 'update'
                            }
                            self.$store.dispatch('FETCH_TOKENISED_FEEDBACK',data);
                        } else if(ov[idx].str == item) {
                            //feedbackQueue.push(ov[idx]);
                        }
                    } else {
                        //new str added to the the editor so get analysis
                        //changedIds.push(idx);
                        let b=idx;
                        let data = {
                            'send': {'txt': item, 'action': 'quick', 'extra': self.attributes},
                            'idx': idx,
                            'act': 'add'
                        }
                        self.$store.dispatch('FETCH_TOKENISED_FEEDBACK',data);
                    }
                });
                //EventBus.$emit('compute-done', feedbackQueue);
            },
            fetchFeedback(type) {
                this.errors=[];
                //this.autoCheck = true;
                this.attributes.initFeedback = true;
                this.btnFeedback = true;
                window.trackEvent('Feedback', 'get', this.preSetAssignment.assignment.code);
                if(this.feedbackOpt!=='') {
                    // let data = {'tap': this.tap, 'txt':'', 'action': 'fetch', 'extra': this.attributes};
                    let data = {
                        'txt':this.editorContent,
                        'action': 'fetch',
                        'extra': this.attributes,
                        'type':type,
                        'document':this.preSetAssignment.id,
                        'currentFeedback': this.feedback
                    };
                    this.$store.dispatch('LOAD_FEEDBACK',data);
                    //this.autoCheck = false;
                } else {
                    this.$data.errors.push({'message':'Please select feedback type'});
                }
            },
            quickCheck() {
                if (this.editorContent !== '') {
                    this.tokeniseTextInput();
                }
            },
            storeAnalysedDrafts() {
                // this.$data.tap='';
                //this.$data.auto='processing....';
                if (this.editorStore !== this.editorContent) {
                    this.editorStore = this.editorContent;
                    let data = {
                        'txt': this.editorContent,
                        'action': 'store',
                        'extra': this.attributes,
                        'type': 'auto',
                        'document': this.preSetAssignment.id
                    };
                    axios.post('/feedback/store', data)
                        .then(response => {
                            //this.$data.auto = 'Draft saved : '+ moment().format('DD/MM/YYYY hh:mma');
                        })
                        .catch(e => {
                            this.$data.errors.push(e)
                        });
                }

            },
            tokeniseTextInput() {
                //console.log("into tokenise");
                //this.$data.autoCheckr =true;
                this.$data.counter = 0;
                axios.post('/processor', {'txt': this.editorContent, 'action': 'tokenise'})
                    .then(response => {
                        this.splitText = response.data.tokenised;
                        //this.$data.autoCheck=false;
                        this.computeText(this.splitText, this.feedback.final);
                        this.$data.counter = 0;
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            updateAutoFeedback(){
                if(this.autofeedback) {
                    this.intervalId = setInterval(this.quickCheck, 120000);
                } else  {
                    if(this.intervalId > 0) {
                        clearInterval(this.intervalId);
                        this.intervalId =0;
                    }
                }
            },
            autoStore() {
                setInterval(this.storeAnalysedDrafts, 5000);
            },

        }
    }
</script>
