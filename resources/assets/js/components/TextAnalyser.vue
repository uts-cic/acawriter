<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-info">
                    <div class="card-header">
                        <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#a" aria-expanded="false" aria-controls="collapseExample">
                            Status
                        </button>&nbsp;<button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#b" aria-expanded="false" aria-controls="collapseExample">
                        Feedback
                    </button>
                    </div>

                    <div class="card-body collapse" id="a">
                        <p class="card-text">
                            <i class="fa fa-globe"></i> TAP <small>next updated after : {{10- counter}} changes.</small><br/>
                            <i class="fa fa-database" aria-hidden="true"></i> <small>Save: {{auto}} </small>
                        </p>
                    </div>
                    <div class="collapse" id="b">
                        <div class="card card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="grammar">Grammar</label>
                                    <select class="form-control" id="grammar" v-model="attributes.grammar">
                                        <option value="">Select</option>
                                        <option value="reflective">Reflective</option>
                                        <option value="analytic">Analytic</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="feedbackOpt">Feedback Rules</label>
                                    <select class="form-control" id="feedbackOpt" v-model="attributes.feedbackOpt">
                                        <option value="feedback">Reflective01</option>
                                        <option value="feedback">Analytic01</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-default">Text Analyser
                        <!--<button type="button" class="btn btn-outline-info pull-right" v-on:click="fetchFeedback()">Step 2. Get Custom</button>&nbsp;-->
                        <button type="button" class="btn btn-outline-info btn-sm pull-right" v-on:click="fetchAnalysis()">Get Feedback</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="editor">
                                    <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                                    <vue-editor v-model="editorContent"></vue-editor>
                                </div>
                            </div>
                            <!--- Reflective feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'reflective'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'reflective'">
                                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                                    <ul>
                                        <li v-for="error in errors">{{error.message}}</li>
                                    </ul>
                                </div>
                                <span v-show="tapCalls.vocab" class="fa fa-spinner fa-spin"></span>
                                <div class="col-md-12"><h4>Feedback <small>(Reflective)</small></h4></div>
                                <div class="col-md-12 wrapper">
                                    <!--<span v-html="editorContent"></span>-->



                                    <span v-show="tapCalls.athanor" class="fa fa-spinner fa-spin"></span>
                                    <span v-for="(feed,idx) in feedback.final">
                                        <!--<span v-for="(expression, exp) in feed.expression.message">
                                            <span v-bind:class="exp">&nbsp;</span>
                                        </span>
                                        <span v-if="feed.metrics.message.length==0"></span>
                                        <span v-else class="metrics">&nbsp;</span>
                                        <span v-for="(rmoves, mv) in feed.moves.message">
                                            <span v-bind:class="mv">&nbsp;</span>
                                        </span>-->
                                        <span v-for="ic in feed.css">
                                            [<span v-bind:class="ic"></span>]
                                        </span>
                                        <span v-html="feed.str"></span>
                                    </span>
                                </div>
                            </div>
                            <!-- end of reflective -->


                            <!--- Analytic feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'analytic'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'analytic'">
                                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                                    <ul>
                                        <li v-for="error in errors">{{error.message}}</li>
                                    </ul>
                                </div>
                                <span v-show="tapCalls.vocab" class="fa fa-spinner fa-spin"></span>
                                <div class="col-md-12"><h4>Feedback <small>(Analytic)</small></h4></div>
                                <div class="col-md-12 wrapper">
                                    <span v-show="tapCalls.athanor" class="fa fa-spinner fa-spin"></span>
                                    <span v-for="(feed,idx) in feedback.final">
                                        <span v-if="feed.metrics.message.length==0"></span>
                                        <span v-else class="metrics">&nbsp;</span>
                                        <span v-for="(rmoves, mv) in feed.moves.message">
                                            <span class="badge badge-info">{{rmoves}}</span>
                                        </span>
                                        {{feed.str}}
                                    </span>
                                </div>
                            </div>
                            <!-- end of reflective -->

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                Feedback <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" v-for="rule in feedback.rules">
                                <h6 class="card-subtitle mb-2">{{rule.name}}</h6>
                                <div v-for="msg in rule.message">
                                    <span v-for="(m,id) in msg">
                                        <span v-bind:class="id"></span> - <small>{{m}}</small><br />
                                    </span>
                                </div>
                            </div>



                            <!-- <div class="col-md-3"><h6 class="card-subtitle mb-2">Background:</h6>
                                 <span v-for="msg in feedback.background"><i class="fa fa-anchor" aria-hidden="true"></i> - <small>{{msg.message}}</small></span>
                             </div>
                             <div class="col-md-3"><h6 class="card-subtitle mb-2">Metrics:</h6>
                                 <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                 <small>- Sentence is too long and may disengage reader. Break into smaller sentences.</small>
                             </div>
                             <div class="col-md-3"><h6 class="card-subtitle mb-2">Vocab:</h6>
                                 <span v-for="msg in feedback.vocab"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> - <small>{{msg.message}}</small></span>
                             </div>
                             <div class="col-md-3"><h6 class="card-subtitle mb-2">Rhetorical Moves:</h6>
                                 <i class="fa fa-comments" aria-hidden="true"></i>
                                 <small>- Athanor raw feedback, hover over the icon to see the tags</small>
                             </div>-->
                        </div>
                        <div class="row">
                            <div class="col-md-12">{{extractedFeed}}</div>
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

    export default {
        components: {
            VueEditor
        },
        name: 'editor',
        props:['assignment'],
        store,
        data () {
            return {
                preview: '',
                editLog : [],
                config: {
                    events: {
                        'froalaEditor.contentChanged': function (e, editor) {
                        }
                    }
                },
                editorContent: 'Edit Your Content Here!',
                loading: 0,
                tap:[],
                errors:[],
                tapCalls:{
                    'athanor': false,
                    'vocab'  :false
                },
                vocab:'',
                counter:0,
                tempIds:[],
                auto:'',
                splitText:[],
                quickTags:'',
                //feedback:[],
                attributes:{
                    feedbackOpt:'feedback',
                    grammar:'reflective'
                },
                extractedFeed:[]
            }
        },
        mounted () {
            this.editLog.push(this.editorContent);
            this.fetchAnalysis();
           // this.$store.dispatch('LOAD_FEEDBACK');
        },
        created() {
            this.auto = 'every 5m';
            //setInterval(this.storeAnalysedDrafts, 900000);
            setInterval(this.quickCheck, 50000);
            /*EventBus.$on('compute-done', data => {
                 this.feedback.final.forEach (function(exp, idx) {
                     if(exp.str === data.str) {
                            this.feedback.final[idx] = data;
                     }
                 });
            });*/


        },
        computed: {
            reflective: function() {
                return this.attributes.feedbackOpt == 'reflective' ? 'display:inline': '';
            },
            analytic: function() {
                return this.attributes.feedbackOpt == 'analytic' ? 'display:inline': '';
            },
            ...mapGetters({
                feedback: 'currentFeedback'
            })
        },
        watch :{
            /* editorContent: function (newVal) {
                 this.$data.counter++;
             },
             tap: function() {
                 this.fetchFeedback();
             }*/
        },
        methods: {
            fetchAnalysis() {
                this.$data.tapCalls.athanor =true;
                this.$data.counter = 0;
                //this.feedback =[];
                axios.post('/processor', {'txt': this.editorContent, 'action': 'athanor', 'grammar':this.attributes.grammar})
                    .then(response => {
                        this.$data.tap = response.data.athanor;
                        this.$data.tapCalls.athanor=false;
                        this.fetchFeedback();
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
                this.$data.tapCalls.vacab =true;
                axios.post('/processor', {'txt': this.editLog, 'action': 'vocab'})
                    .then(response => {
                        this.$data.tapCalls.vocab=false;
                        this.$data.vocab = response.data.vocab.unique;
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            computeText: function(nv, ov) {
                var changedText='';
                var self = this;
               // var newTap = [];
                var feedbackQueue=[];

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
                            /*self.quickAnalyse(changedText, idx)
                                .then(response => {
                                    if(response.data) {
                                        this.$store.dispatch('UPDATE_TOKENISED_FEEDBACK',response.data.final[0]);
                                        //EventBus.$emit('compute-done', response.data.final[0]);

                                        console.log("274");
                                    }
                                })
                                .catch(e => {
                                    self.$data.errors.push(e)
                                }); */

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
                       /* self.quickAnalyse(changedText, idx)
                            .then(response => {
                                if (response.data) {
                                       //feedbackQueue[b] = response.data.final[0];
                                      // EventBus.$emit('compute-done', response.data.final[0]);
                                       console.log("293");
                                    }
                                })
                                .catch(e => {
                                    self.$data.errors.push(e)
                                });*/

                    }

                });
                //EventBus.$emit('compute-done', feedbackQueue);
            },
            quickAnalyse(changedText, idx) {
                this.$data.counter = 0;
                var quickTags = {};
                return axios.post('/feedback', {'txt': changedText, 'action': 'quick', 'extra': this.attributes});
            },
            storeAnalysedDrafts() {
                console.log("into auto store");
                // this.$data.tap='';
                this.$data.auto='processing....';
                var assignment_id=0;
                if(this.assignment!=="") {assignment_id= this.assignment;}
                axios.post('/processor', {'txt': this.editorContent, 'action': 'auto', 'assignment_id':assignment_id})
                    .then(response => {
                        this.$data.auto = 'Done';
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            tokeniseTextInput() {
                console.log("into tokenise");
                this.$data.tapCalls.athanor =true;
                this.$data.counter = 0;
                axios.post('/processor', {'txt': this.editorContent, 'action': 'tokenise'})
                    .then(response => {
                        this.splitText = response.data.tokenised;
                        this.$data.tapCalls.athanor=false;
                        this.computeText(this.splitText, this.feedback.final);
                        this.$data.counter = 0;
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            fetchFeedback() {
                this.errors=[];
                if(this.feedbackOpt!=='') {
                    let data = {'tap': this.tap, 'txt':'', 'action': 'fetch', 'extra': this.attributes};
                    this.$store.dispatch('LOAD_FEEDBACK',data);



                    /*axios.post('/feedback', {'tap': this.tap, 'txt':'', 'action': 'fetch', 'extra': this.attributes})
                        .then(response => {
                            this.feedback = response.data;
                        })
                        .catch(e => {
                            this.$data.errors.push(e)
                        });*/
                } else {
                    this.$data.errors.push({'message':'Please select feedback type'});
                }
            },
            quickCheck() {
                if (this.editorContent !== '') {
                    this.tokeniseTextInput();
                }
            }
        },

    }
</script>
