<template>
    <div class="mb-5" data-ga-category="Example">
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
                    <div class="subheader-title">
                        <h4 v-if="preSetAssignment">Example: {{preSetAssignment.title}}</h4>
                    </div>
                </div>

                <div v-if="auto!=''"><small>{{auto}}</small></div>
                <p>This is an example piece of writing to experiment with. You can edit this text (nothing is saved).
                    Click Get Feedback to see the automatic feedback on the right.
                    <br>When you're ready, go to <a href="/">My documents</a> and create your own document.</p>

                <div class="feedback">
                    <div class="feedback-col" id="original">
                        <div id="editor">
                            <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                            <vue-editor v-model="editorContent" :editorToolbar="customToolbar" placeholder="Place your text here..."></vue-editor>
                        </div>
                    </div>

                    <div class="feedback-action">
                        <button type="button" v-bind:disabled="getbtnStatus" class="btn btn-primary" v-on:click="fetchFeedback('manual')">Get Feedback <i class="fa fa-angle-right"></i></button>
                    </div>

                    <!-- Reflective feedback -->
                    <div class="feedback-col" id="parsed">
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
        props:['ex', 'role', 'ext'],
        store,
        data () {
            return {
                editorContent: '',
                loading: 0,
                tap:[],
                errors:[],
                counter:0,
                tempIds:[],
                auto:'',
                autosave:'',
                example:{
                    faculty:'',
                    title:'',
                    summary:'',
                    genre:1
                },
                splitText:[],
                quickTags:'',
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
                initFeedback:false
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
                return this.attributes.feedbackOpt == 'analytical' ? 'display:inline': '';
            },
            ...mapGetters({
                feedback: 'currentFeedback',
                processing: 'loadingStatus'
            }),
            admin: function() {
                return this.role;
            },
            preSetAssignment: function() {
                console.log(JSON.parse(this.ex));
                if(this.ex) {
                    return JSON.parse(this.ex);
                } else {
                    return false;
                }
            },
            features: function() {
              return JSON.parse(this.ext);
            },
            grammar: function() {
                var self =this;
                let gram ='';
                for( var [k ,v] of Object.entries(this.features)) {
                    v.forEach(function(item){
                        if(item.id == self.example.genre)  {
                            gram =  k.toLowerCase();
                        }
                    });
                }
                return gram;
            },
            attributes: function() {
                if(this.preSetAssignment) {
                    if(this.preSetAssignment.raw_response) {
                        this.editorContent = this.preSetAssignment.text_input;

                        let data = {'savedFeed':JSON.parse(this.preSetAssignment.raw_response)};
                        this.$store.dispatch('PRELOAD_FEEDBACK',data);
                        this.initFeedback = false;
                    }
                    let feature = this.preSetAssignment.feature;
                    return {
                        feedbackOpt:feature.grammar.toLowerCase() == 'analytical' ? 'a_01': 'r_01',
                        grammar: feature.grammar.toLocaleLowerCase(),
                        isResearch: feature.id === 10 || feature.id === 5,
                        feature: feature.id,
                        storeDraftJobRef: Math.random().toString(36).substring(7),
                        initFeedback:this.initFeedback

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
                //setInterval(this.storeAnalysedDrafts('auto'), 900000);
            },
            rulesClasses: function() {
                let rules = [];
                rules = this.feedback.rules.map(function (rule, idx) {
                    return rule.css.map(function (cl) {
                        return cl;
                    });
                });
                let classes = [].concat(...rules);
                return classes;
            }


        },
        watch :{
        },
        methods: {
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
            },storeAnalysedDrafts() {
                this.$data.auto='processing....';
                this.attributes.initFeedback = true;
                let data = {'txt':this.editorContent, 'action': 'store', 'extra': this.attributes,'feedback':this.feedback, 'other':this.example};
                axios.post('/example/store', data)
                    .then(response => {
                        this.$data.auto = 'Stored : '+ moment().format('DD/MM/YYYY hh:mma');
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            getGrammar: function(idx) {
                for( var [k ,v] of Object.entries(this.features)) {
                    v.forEach(function(item){
                        if(item.id == idx)  {
                            return k.toLowerCase();
                        }
                    });
                }
            }

        }
    }
</script>
