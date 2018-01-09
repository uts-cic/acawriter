<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8"><h3 v-if="preSetAssignment">{{preSetAssignment.name}}</h3></div>
            <div class="col-md-4">
                <div class="card bg-default">
                    <div class="card-header">
                        <button class="btn btn-info btn-sm" type="button" data-toggle="collapse" data-target="#b" aria-expanded="false" aria-controls="collapseExample">
                            Feedback
                        </button>
                    </div>
                    <div class="card-body collapse" id="a">
                        <p class="card-text text-white">
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
                                        <option value="r_01">Reflective01</option>
                                        <option value="a_01">Analytic01</option>
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
                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                    <ul>
                        <li v-for="error in errors">{{error.message}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row editWrapper">
            <div id="sidebar">
                <div class="p-3 bg-uts-primary text-white"><i class="fa fa-info-circle" aria-hidden="true"></i> Feedback Guide
                    <i class="fa fa-times-circle pull-right" aria-hidden="true" id="sidebarCollapseTwice"></i>
                </div>
                <div class="col-md-12 col-xs-12" v-for="rule in feedback.rules">
                     <h6 class="card-subtitle mb-2">{{rule.name}}</h6>
                    <div v-for="msg in rule.message">
                       <span v-for="(m,id) in msg">
                           <i class="fa fa-toggle-on text-success" aria-hidden="true"></i> &nbsp; &nbsp;
                           <span v-bind:class="id"></span> <small><span v-html="m"></span></small><br />
                       </span>

                    </div>
                    <hr />
                </div>
            </div>


            <!-- start content -->
            <div id="content" class="col-md-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">Document Analyser
                        <div class="btn-group pull-right" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" class="btn brand-btn-outline-secondary btn-sm" v-on:click="fetchFeedback()"><i class="fa fa-cloud-download"  aria-hidden="true"></i> Get Feedback</button>
                            <button type="button" class="btn brand-btn-outline-secondary btn-sm"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                            <button type="button" class="btn brand-btn-outline-secondary btn-sm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Export to PDF</button>
                            <button type="button" id="sidebarCollapse" class="btn brand-btn-outline-secondary btn-sm"><i class="fa fa-info-circle" aria-hidden="true"></i> Feedback Guide</button>

                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="editor">
                                    <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                                    <vue-editor v-model="editorContent" :editorToolbar="customToolbar"></vue-editor>
                                </div>
                            </div>
                            <!--- Reflective feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'reflective'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'reflective'">

                                <div class="col-md-12"><h4>Feedback <small>(Reflective)</small></h4>
                                    <span v-if="processing"  class="text-success">
                                        <i class="fa fa-spinner fa-spin"></i> Processing text .....
                                        <span class="sr-only">Loading...</span>
                                    </span>
                                <hr /></div>
                                <div class="col-md-12 wrapper">
                                    <!--<span v-html="editorContent"></span>-->

                                    <span v-for="(feed,idx) in feedback.final">
                                        <span v-for="ic in feed.css">
                                            <template v-if="ic==='context' || ic==='challenge' || ic==='change' || ic==='metrics' || ic==='affect'">
                                                <span v-bind:class="ic"></span>
                                            </template>
                                        </span>
                                       <!-- &nbsp;<span v-html="feed.str" v-bind:class="[inLineClasses(feed.css)]"></span> -->
                                        <span v-html="inText(feed)" v-bind:class="[inLineClasses(feed.css)]"></span>

                                    </span>
                                </div>
                            </div>
                            <!-- end of reflective -->


                            <!--- Analytic feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'analytic'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'analytic'">
                                <div class="col-md-12"><h4>Feedback <small>(Analytical)</small></h4></div>
                                <div class="col-md-12 wrapper">
                                    <span v-for="(feed,idx) in feedback.final">
                                        <span v-if="feed.metrics.message.length==0"></span>
                                        <span v-else class="metrics">&nbsp;</span>
                                        <span v-for="(rmoves, mv) in feed.moves.message">
                                            <span class="badge badge-pill badge-analytic">{{rmoves}}</span>
                                        </span>
                                        <span v-html="feed.str"></span>
                                    </span>
                                </div>
                            </div>
                            <!-- end of analytics -->

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <small>Note: Feedback updated and stored automatically every 5 mins</small>
                            </div>
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
               /* attributes:{
                    feedbackOpt:'r_01',
                    grammar:this.preSetAssignment.feature.grammar? this.preSetAssignment.feature.grammar : 'reflective'
                },*/
                customToolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ]

            }
        },
        mounted () {
            this.editLog.push(this.editorContent);
            //this.fetchAnalysis();
            this.fetchFeedback();
            // this.$store.dispatch('LOAD_FEEDBACK');
        },
        created() {
            this.auto = 'every 5m';
            //setInterval(this.storeAnalysedDrafts, 900000);
            setInterval(this.quickCheck, 300000);
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
                feedback: 'currentFeedback',
                processing: 'loadingStatus'
            }),
            preSetAssignment: function() {
                if(this.assignment) {
                    return JSON.parse(this.assignment);
                } else {
                    return false;
                }
            },
            attributes: function() {
                if(this.preSetAssignment) {
                    return {
                        feedbackOpt:this.preSetAssignment.feature.grammar.toLowerCase() == 'analytic' ? 'a_01': 'r_01',
                        grammar: this.preSetAssignment.feature.grammar.toLocaleLowerCase()
                    };
                } else {
                   return {
                        feedbackOpt:'a_01',
                        grammar: 'analytic'
                   };
                }
            }
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
                this.$data.tapCalls.vocab =true;
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
                this.$data.autoCheckr =true;
                this.$data.counter = 0;
                axios.post('/processor', {'txt': this.editorContent, 'action': 'tokenise'})
                    .then(response => {
                        this.splitText = response.data.tokenised;
                        this.$data.autoCheck=false;
                        this.computeText(this.splitText, this.feedback.final);
                        this.$data.counter = 0;
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            fetchFeedback() {
                this.errors=[];
                this.autoCheck = true;
                if(this.feedbackOpt!=='') {
                   // let data = {'tap': this.tap, 'txt':'', 'action': 'fetch', 'extra': this.attributes};
                    let data = {'txt':this.editorContent, 'action': 'fetch', 'extra': this.attributes};
                    this.$store.dispatch('LOAD_FEEDBACK',data);
                    this.autoCheck = false;
                } else {
                    this.$data.errors.push({'message':'Please select feedback type'});
                }
            },
            quickCheck() {
                if (this.editorContent !== '') {
                    this.tokeniseTextInput();
                }
            },
            inLineClasses: function(data) {
                var temp=  data.filter(function( obj ) {
                    return (obj ==='link2me');
                });
                return temp;
            },
            inText: function(data) {
                if(data.str!=='' && typeof data.expression!== 'undefined') {
                    let str = data.str;
                    let inT;
                    if(data.expression.affect.length > 0) {
                        data.expression.affect.forEach(function(word) {
                            str = str.replace(word.text, "<span class='affect'>"+word.text+"</span>");
                        });

                    }
                    if(data.expression.epistemic.length > 0) {
                        data.expression.epistemic.forEach(function(word) {
                            str = str.replace(word.text, "<span class='epistemic'>"+word.text+"</span>");
                        });
                    }
                    if(data.expression.modal.length > 0) {
                        data.expression.modal.forEach(function(word) {
                            str = str.replace(word.text, "<span class='modall'>"+word.text+"</span>");
                        });
                    }
                    return str;


                } else {
                    let str = '';
                    return str;
                }
            }
        }
    }
</script>