<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6"><h3 v-if="preSetAssignment">{{preSetAssignment.name}}</h3></div>
            <div class="col-md-4 text-success">
                <span v-if="draftUpdate.message!=''">{{draftUpdate.message}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                    <ul>
                        <li v-for="error in errors">{{error.message}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row editWrapper">
            <div id="sidebar" class="active" v-bind:class="this.attributes.grammar == 'analytic'? 'ana' : 'ref'">
                <div class="p-3 bg-uts-primary text-white"><i class="fa fa-info-circle" aria-hidden="true"></i> Key
                    <i class="fa fa-times-circle pull-right" aria-hidden="true" id="sidebarCollapseTwice"></i>
                    <i class="fa fa-window-restore pull-right" aria-hidden="true" id="extendOut"></i>&nbsp;&nbsp;
                </div>
                <div class="col-md-12 col-xs-12" v-for="rule in feedback.rules">
                     <h6 class="card-subtitle mb-2">&nbsp;</h6>
                    <div v-for="msg in rule.message">
                        <div class="row" v-for="(m,id) in msg">
                            <div class="col-md-1 col-xs-1"><input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"></div>
                            <div class="col-md-10 col-xs-11"><span v-bind:class="id"></span>&nbsp;<span v-html="m"></span></div>
                        </div>
                    </div>
                    <hr />
                </div>
            </div>


            <!-- start content -->
            <div id="content" class="col-md-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <div class="row">
                            <div class="col-md-2">Document Analyser</div>
                            <div class="col-md-2 text-right">
                                <span class="text-white" v-if="auto!=''"><small>{{auto}}</small></span>
                            </div>

                            <div class="col-md-8 text-right">Auto feedback: <input type="checkbox" v-model="autofeedback" v-on:change="updateAutoFeedback()"/> &nbsp; &nbsp;
                                <div class="btn-group pull-right" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn brand-btn-outline-secondary btn-sm" v-on:click="fetchFeedback()"><i class="fa fa-cloud-download"  aria-hidden="true"></i> Get Feedback</button>&nbsp;
                                    <button type="button" class="btn brand-btn-outline-secondary btn-sm" v-on:click="storeAnalysedDrafts('manual')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>&nbsp;
                                    <button type="button" class="btn brand-btn-outline-secondary btn-sm disabled"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export to PDF</button>&nbsp;
                                    <button type="button" id="sidebarCollapse" class="btn brand-btn-outline-secondary btn-sm"><i class="fa fa-info-circle" aria-hidden="true"></i> Key</button>

                                </div>
                            </div>
                    </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="editor">
                                    <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                                    <p><small>AcaWriter works fastest with short texts, so if you're only working on a specific section, don't paste in the whole document. It still processes long texts, but it may take a few minutes to get your feedback to you.</small></p>
                                    <vue-editor v-model="editorContent" :editorToolbar="customToolbar"></vue-editor>
                                </div>
                            </div>
                            <!--- Reflective feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'reflective'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'reflective'">
                                <p><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></p>
                                <reflective-result></reflective-result>
                            </div>
                            <!-- end of reflective -->


                            <!--- Analytic feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'analytic'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'analytic'">
                                <p><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></p>
                                <analytic-result></analytic-result>
                            </div>
                            <!-- end of analytics -->

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script>
    /**\
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

    export default {
        components: {
            VueEditor,
            reflectiveResult: Reflective,
            analyticResult: Analytic
        },
        name: 'editor',
        props:['document', 'userActivity'],
        store,
        data () {
            return {
                editorContent: 'Edit Your Content Here!',
                loading: 0,
                tap:[],
                errors:[],
                counter:0,
                tempIds:[],
                auto:'',
                autosave:'',
                autofeedback:false,
                splitText:[],
                quickTags:'',
                customToolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ],
                cssSpec: {
                    inline :['link2me'],
                    iconic :['context', 'challenge', 'change', 'metrics', 'affect'],
                    inText :['affect', 'epistemic','modall']
                },
                analytic_xlator:[
                    {'metrics': 'metrics'},
                    {'emph': 'E'},
                    {'vis': 'T'},
                    {'contrast': 'C'},
                    {'contribution': 'S'},
                    {'nostat': 'N'},
                    {'tempstat': 'B'},
                    {'attitude': 'P'},
                ],
                initFeedback:true,
                intervalId:0
            }
        },
        mounted () {
            if(this.initFeedback) {
                this.fetchFeedback();
            }
        },
        created() {
            this.auto = '';
            //setInterval(this.storeAnalysedDrafts, 900000);
           // setInterval(this.quickCheck, 300000);
        },
        computed: {
            reflective: function() {
                return this.attributes.feedbackOpt == 'reflective' ? 'display:inline': '';
            },
            analytic: function() {
                return this.attributes.feedbackOpt == 'analytic' ? 'display:inline': '';
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
                    }
                    let feature = this.preSetAssignment.feature[0];
                    return {
                        feedbackOpt:feature.grammar.toLowerCase() == 'analytic' ? 'a_01': 'r_01',
                        grammar: feature.grammar.toLocaleLowerCase(),
                        feature: feature.id,
                        storeDraftJobRef: Math.random().toString(36).substring(7)
                    };
                } else {
                   return {
                        feedbackOpt:'a_01',
                        grammar: 'analytic',
                        feature:0,
                        storeDraftJobRef: Math.random().toString(36).substring(7)
                   };
                }
                setInterval(this.storeAnalysedDrafts('auto'), 300000);
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
                var s = this;
                if(this.userActivity && this.preSetAssignment){
                    //console.log(this.slogs.details.status);
                    this.userActivity.forEach(function(activity){
                        if(activity.data) {
                            if(activity.data.type==='Draft' && activity.data.ref === s.attributes.storeDraftJobRef) {
                                upd.message = "Draft Saved " + moment().format('DD/MM/YYYY hh:mma');
                                s.auto = "Draft Saved " + moment().format('DD/MM/YYYY hh:mma');
                            }
                        }
                    });
                }

                return upd;
            },
        },
        watch :{
        },
        methods: {
            computeText: function(nv, ov) {
                var changedText='';
                var self = this;
                // var newTap = [];
                var feedbackQueue=[];
                if(nv.length===0) return;
                nv.forEach(function(item, idx) {
                    //console.log(item);
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
                            //console.log("283");
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
            fetchFeedback() {
                this.errors=[];
                //this.autoCheck = true;
                if(this.feedbackOpt!=='') {
                    // let data = {'tap': this.tap, 'txt':'', 'action': 'fetch', 'extra': this.attributes};
                    let data = {'txt':this.editorContent, 'action': 'fetch', 'extra': this.attributes};
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
            storeAnalysedDrafts(type) {
                console.log("into auto store");
                // this.$data.tap='';
                this.$data.auto='processing....';
                let data = {'txt':this.editorContent, 'action': 'store', 'extra': this.attributes, 'type':type, 'document':this.preSetAssignment.id};
                axios.post('/feedback/store', data)
                    .then(response => {
                        //this.$data.auto = 'Draft saved : '+ moment().format('DD/MM/YYYY hh:mma');
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            tokeniseTextInput() {
                console.log("into tokenise");
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
            }
        }
    }
</script>