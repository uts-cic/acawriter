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
                                    <vue-editor v-model="editorContent" placeholder="Place your text here..."></vue-editor>
                                </div>
                            </div>
                            <!-- Reflective feedback -->
                            <div class="col-md-6" v-bind:class="this.attributes.grammar == 'reflective'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'reflective'">
                                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                                    <ul>
                                        <li v-for="error in errors">{{error.message}}</li>
                                    </ul>
                                </div>
                                <span v-show="tapCalls.vocab" class="fa fa-spinner fa-spin"></span>
                                <div class="col-md-12"><h4>Feedback <small>(Reflective)</small></h4></div>
                                <div class="col-md-12 wrapper">
                                    <span v-show="tapCalls.athanor" class="fa fa-spinner fa-spin"></span>
                                    <span v-for="(feed,idx) in feedback.final">
                                        <span v-for="(expression, exp) in feed.expression.message">
                                            <span v-bind:class="exp">&nbsp;</span>
                                        </span>
                                        <span v-if="feed.metrics.message.length==0"></span>
                                        <span v-else class="metrics">&nbsp;</span>
                                        <span v-for="(rmoves, mv) in feed.moves.message">
                                            <span v-bind:class="mv">&nbsp;</span>
                                        </span>
                                        {{feed.str}}
                                    </span>
                                </div>
                            </div>
                            <!-- end of reflective -->


                            <!-- Analytic feedback -->
                            <div class="col-md-6" v-bind:class="this.attributes.grammar == 'analytic'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'analytic'">
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
    import {VueEditor} from 'vue2-editor';
   export default {
       components: {
           VueEditor
       },
       name: 'editor',
       props:['assignment'],
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
               editorContent: '',
               tmp: 'More text',
               viewer : {},
               loading: 0,
               tap:[],
               qtap:[],
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
               feedback:[],
               attributes:{
                   feedbackOpt:'feedback',
                   grammar:'reflective'
               }
           }
       },
       mounted () {
           this.editLog.push(this.editorContent);
           this.fetchAnalysis();
       },
       created() {
           this.auto = 'every 5m';
           //setInterval(this.storeAnalysedDrafts, 900000);
       },
       watch :{
           editorContent: function (newVal) {
               this.$data.counter++;
               if(this.$data.counter >= 10 ) {
                   this.tokeniseTextInput();
                   this.computeText(this.splitText, this.$data.tap);
                   this.$data.counter = 0;
               }
           }
        },
       methods: {
           fetchAnalysis() {
               this.$data.tapCalls.athanor =true;
               this.$data.counter = 0;
               axios.post('/processor', {'txt': this.editorContent, 'action': 'athanor', 'grammar':this.attributes.grammar})
                   .then(response => {
                       this.$data.tap = response.data.athanor;
                       this.$data.tapCalls.athanor=false;
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
               var newTap = [];
               nv.forEach(function(item, idx) {
                   //console.log(item);
                   var temp = {};
                   var qt={};
                   if(typeof ov[idx]!=='undefined') {
                       if(ov[idx].str!= item) {
                           changedText = item;
                           temp['str'] = changedText;
                           self.quickAnalyse(changedText, idx)
                               .then(response => {
                                   if(response.data) {
                                       temp['str'] = response.data.athanor.str;
                                       temp['tags'] = response.data.athanor.tags;
                                       temp['raw_tags'] = response.data.athanor.raw_tags;
                                   }
                               })
                               .catch(e => {
                                   this.$data.errors.push(e)
                               });
                       } else if(ov[idx].str == item) {
                           temp['str'] = ov[idx].str;
                           temp['tags'] = ov[idx].tags;
                           temp['raw_tags'] = ov[idx].raw_tags;
                       }
                   } else {
                       //changedIds.push(idx);
                       changedText = item;
                       //qt = self.quickAnalyse(changedText, idx);
                       self.quickAnalyse(changedText, idx)
                           .then(response => {
                               if(response.data) {
                                   temp['str'] = response.data.athanor.str;
                                   temp['tags'] = response.data.athanor.tags;
                                   temp['raw_tags'] = response.data.athanor.raw_tags;
                               }
                           })
                           .catch(e => {
                               this.$data.errors.push(e)
                           });
                   }
                   newTap.push(temp);
               });
               self.tap = newTap;
           },
           quickAnalyse(changedText, idx) {
               this.$data.counter = 0;
               var quickTags = {};
               return axios.post('/processor', {'txt': changedText, 'action': 'qathanor'});
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
                   })
                   .catch(e => {
                       this.$data.errors.push(e)
                   });
           },
           fetchFeedback() {
               this.errors=[];
               if(this.feedbackOpt!=='') {
                   axios.post('/feedback', {'tap': this.tap, 'action': 'fetch', 'extra': this.attributes})
                       .then(response => {
                           this.feedback = response.data;
                       })
                       .catch(e => {
                           this.$data.errors.push(e)
                       });
               } else {
                   this.$data.errors.push({'message':'Please select feedback type'});
               }
           },
           qfetchFeedback() {
               this.errors=[];
               if(this.feedbackOpt!=='') {
                   axios.post('/feedback', {'tap': this.tap, 'action': 'fetch', 'extra': this.attributes})
                       .then(response => {
                           this.feedback = response.data;
                       })
                       .catch(e => {
                           this.$data.errors.push(e)
                       });
               } else {
                   this.$data.errors.push({'message':'Please select feedback type'});
               }
           }
       },
       computed: {
           reflective: function() {
              return this.attributes.feedbackOpt == 'reflective' ? 'display:inline': '';
           },
           analytic: function() {
               return this.attributes.feedbackOpt == 'analytic' ? 'display:inline': '';
           }

       }
   }
</script>
