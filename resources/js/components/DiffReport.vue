<template>
    <div class="mb-5" data-ga-category="Feedback">
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

                <div class="feedback">
                    <div class="feedback-col" id="original">
                        <div id="editor">
                            <vue-editor v-model="editorContent" :editorToolbar="customToolbar" placeholder="Place your text here..."></vue-editor>
                        </div>
                    </div>

                    <div class="feedback-action">

                    </div>

                    <!-- Reflective feedback -->
                    <div class="feedback-col" id="parsed">
                       <div v-if="this.attributes.grammar == 'reflective'">
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
    const EventBus = new Vue();
    import { VueEditor } from 'vue2-editor';
    import * as diff from 'diff';
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
        props:['document', 'document_compare', 'userActivity'],
        store,
        data () {
            return {
                editorContent: '',
                compareContent: '',
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
             this.autoStore();
        },
        created() {
            this.auto = '';
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
            compareDocument: function() {
                if (this.document_compare) {
                    return JSON.parse(this.document_compare)
                } else {
                    return false;
                }
            },
            diffDocument: function() {
                let diff_texts = this.computeDiffTexts();
                return this.highlighText(diff_texts);
            },
            diffFeedback: function() {
                let diff_feedback = this.computeDiffFeedback();
                return this.highlighTextFeedback(diff_feedback);
            },
            attributes: function() {
                if(this.preSetAssignment) {
                    this.editorContent = this.preSetAssignment.text_input;
                    this.preSetAssignment.raw_response = this.diffFeedback;
                    let data = {'savedFeed':this.preSetAssignment.raw_response};
                    console.log(this.preSetAssignment.raw_response.rules)
                    this.$store.dispatch('PRELOAD_FEEDBACK',data);
                    this.initFeedback = false;
                    let feature = this.preSetAssignment.features;
                    this.editorContent = this.diffDocument;
                    return {
                        feedbackOpt:feature.grammar.toLowerCase() == 'analytical' ? 'a_01': 'r_01',
                        grammar: feature.grammar.toLocaleLowerCase(),
                        feature: feature.id,
                        storeDraftJobRef: Math.random().toString(36).substring(7),
                        initFeedback:this.initFeedback
                    };
                } else {
                   return {
                        feedbackOpt:'a_01',
                        grammar: 'analytical',
                        feature:0,
                        storeDraftJobRef: Math.random().toString(36).substring(7),
                       initFeedback:this.initFeedback
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
            },
            getbtnStatus: function() {
                if (this.btnFeedback) {
                    return true;
                }  else {
                    return false;
                }
            },
            getLink:function() {
                let link='/generate-pdf/';
                let data ={};

                if(this.preSetAssignment) {
                    data.id = (this.preSetAssignment.id * 123456); //this is the document id
                    data.grammar = this.preSetAssignment.features.grammar.toLocaleLowerCase();
                    data.name= this.preSetAssignment.name;
                }
                return link+ JSON.stringify(data);
            }
        },
        watch :{
            'editorContent': function(val, oldVal) {
            }
        },
        methods: {
            computeText: function(nv, ov) {
                var changedText='';
                var self = this;
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
                        }
                    } else {
                        let b=idx;
                        let data = {
                            'send': {'txt': item, 'action': 'quick', 'extra': self.attributes},
                            'idx': idx,
                            'act': 'add'
                        }
                        self.$store.dispatch('FETCH_TOKENISED_FEEDBACK',data);
                    }
                });
            },
            fetchFeedback(type) {
                this.errors=[];
                this.attributes.initFeedback = true;
                this.btnFeedback = true;
                if(this.feedbackOpt!=='') {
                    let data = {
                        'txt':this.editorContent,
                        'action': 'fetch',
                        'extra': this.attributes,
                        'type':type,
                        'document':this.preSetAssignment.id,
                        'currentFeedback': this.feedback
                    };
                    this.$store.dispatch('LOAD_FEEDBACK',data);
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
                        })
                        .catch(e => {
                            this.$data.errors.push(e)
                        });
                }

            },
            tokeniseTextInput() {
                this.$data.counter = 0;
                axios.post('/processor', {'txt': this.editorContent, 'action': 'tokenise'})
                    .then(response => {
                        this.splitText = response.data.tokenised;
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
            computeDiffTexts() {
                var diff_texts = diff.diffWords(this.preSetAssignment.text_input, this.compareDocument.text_input);
                return diff_texts;
            },
            computeDiffFeedback() {
                var tags1 = "";
                var tags2 = "";
                let keys = Object.keys(this.preSetAssignment.raw_response);
                keys.forEach(key => {
                    if (this.preSetAssignment.raw_response[key].length > 0) {
                        for (const [i] of this.preSetAssignment.raw_response[key].entries()) {
                            if (this.preSetAssignment.raw_response[key][i].tags) {
                                let tags = this.preSetAssignment.raw_response[key][i].tags.split(',');
                                if (tags.length > 0) {
                                    tags.forEach(tag => {
                                        tags1 += tag;
                                    })
                                    tags1 += " ";
                                }
                            }
                        }
                    }
                    if (this.compareDocument.raw_response[key].length > 0) {
                        for (const [i] of this.compareDocument.raw_response[key].entries()) {
                            if (this.compareDocument.raw_response[key][i].tags) {
                                let tags = this.compareDocument.raw_response[key][i].tags.split(',');
                                if (tags.length > 0) {
                                    tags.forEach(tag => {
                                        tags2 += tag;
                                    })
                                    tags2 += " ";
                                }
                            }
                        }
                    }
                });
                let diff_feedback = diff.diffWords(tags1, tags2);
                return diff_feedback;
            },
            highlighText(diff_texts) {
                var texts_with_diff = "";
                var text_string = "";
                diff_texts.forEach(function(part){
                    text_string = "";
                    // this part is a little bit compplicating
                    // I haven't figured it out yet, but apparently
                    // it works in a reverse way. So, added will be coloured in RED (#F00)
                    // and removed will be coloured in GREEN (#0C0)  
                    if (part.added) {
                        text_string = "<span style=\"background-color: #F00; color: rgb(0, 0, 0);\">" + part.value + "</span> "
                    } else if (part.removed) {
                        text_string = "<span style=\"background-color: #0C0; color: rgb(0, 0, 0);\">" + part.value + "</span> "
                    } else {
                        text_string = part.value
                    }
                texts_with_diff += text_string;
                })
                return texts_with_diff;
            },
            highlighTextFeedback(diff_feedback) {
                var that = this;
                diff_feedback.forEach(function(part) {
                    if (part.added) {
                        that.preSetAssignment.raw_response.rules[2].message.forEach((message, i) => {
                            if (message[part.value.slice(0, -1)]) {
                                that.preSetAssignment.raw_response.rules[2].message[i][part.value.slice(0, -1)] = "<span style=\"background-color: #F00; color: rgb(0, 0, 0);\">" + message[part.value.slice(0, -1)] + "</span> "
                            }
                        })
                    }
                    if (part.removed) {
                        that.preSetAssignment.raw_response.rules[2].message.forEach((message, i) => {
                            if (message[part.value.slice(0, -1)]) {
                                that.preSetAssignment.raw_response.rules[2].message[i][part.value.slice(0, -1)] = "<span style=\"background-color: #0C0; color: rgb(0, 0, 0);\">" + message[part.value.slice(0, -1)] + "</span> "
                            }
                        })
                    }
                })
                return that.preSetAssignment.raw_response;
            }
        }
    }
</script>
