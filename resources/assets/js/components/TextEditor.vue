<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#a" aria-expanded="false" aria-controls="collapseExample">
                            Status
                        </button>&nbsp;<button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#b" aria-expanded="false" aria-controls="collapseExample">
                        Feedback
                        </button>
                    </div>

                    <div class="card-block collapse" id="a">
                        <p class="card-text">
                        <i class="fa fa-globe"></i> TAP <small>next updated after : {{10- counter}} changes.</small><br/>
                        <i class="fa fa-database" aria-hidden="true"></i> <small>Save: {{auto}} </small>
                        </p>
                    </div>
                    <div class="collapse" id="b">
                    <div class="card card-block">
                        <select>
                            <option value="feedback.json">Default</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Text Analyser
                        <button type="button" class="btn btn-outline-primary pull-right" v-on:click="fetchAnalysis()">Get Feedback</button>
                        <button type="button" class="btn btn-outline-primary pull-right" v-on:click="fetchFeedback()">Get Custom</button>

                    </div>
                    <div class="card-body">
                        <div id="editor">
                           <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                            <vue-editor v-model="editorContent"></vue-editor>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Load Feedback</div>
                    <div class="card-body">

                    </div>
                </div>
                <!--<div class="card">
                    <div class="card-header">Features</div>
                    <div class="card-body">
                        <ul>
                            <li class="list-item-group">Vocabulary: <span class="badge badge-info">{{vocab}} </span></li>
                            <li class="list-item-group">Athanor</li>
                        </ul>
                    </div>
                </div> -->
                <div class="card text-white bg-info">
                    <div class="card-header">Feedback</div>
                    <div class="card-body">
                        <span v-for="msg in feedback.temporality"><small>{{msg.message}}</small></span>
                        <hr />
                        <h6 class="card-subtitle mb-2">Metrics:</h6>
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                        <small>- Sentence is too long and may disengage reader. Break into smaller sentences.</small>
                        <!-- <span v-for="d in feedback.metrics">
                            <span v-if="d.message!==''" ><small>{{d.message}}</small></span>
                        </span> -->
                        <hr />
                        <h6 class="card-subtitle mb-2">Rhetorical Moves:</h6>
                        <i class="fa fa-comments" aria-hidden="true"></i>
                        <small>- Athanor raw feedback, hover over the icon to see the tags</small>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <ul v-if="errors && errors.length">
                <li v-for="error in errors">{{error.message}}</li>
            </ul>
            <span v-show="tapCalls.vocab" class="fa fa-spinner fa-spin"></span>
            <div class="col-md-12"><h4>TAP Raw output:</h4></div>
            <div class="col-md-8">
                <span v-show="tapCalls.athanor" class="fa fa-spinner fa-spin"></span>
                <span v-for="(feed,idx) in tap">
                    [<span class="badge bg-default" data-toggle="tooltip" data-placement="left" v-bind:title="feed.tags"><i class="fa fa-comments" aria-hidden="true"></i></span>]
                    <span v-if="!feedback.metrics">
                        {{feed.str}}
                    </span>
                    <span v-else>
                        <span v-if="feedback.metrics[idx].message!==''">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{feed.str}}
                        </span>
                        <span v-else>
                            {{feed.str}}
                        </span>
                    </span>


                </span>


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
               editorContent: 'Edit Your Content Here!',
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
               feedback:[]


           }
       },
      /* apollo:{
           viewer: {
               query: POSTS_QUERY,
               loadingKey: 'loading'
           }
       },*/
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
                   this.editLog.push(this.editorContent);
                   this.$data.counter = 0;
               }
           }
        },
       methods: {
           fetchAnalysis() {
               this.$data.tapCalls.athanor =true;
               this.$data.counter = 0;
               axios.post('/processor', {'txt': this.editorContent, 'action': 'athanor'})
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
           /*checkEligibility: function(nv, ov) {

               var changedText='';
               var self = this;
               nv.forEach(function(item, idx) {
                   //console.log(item);
                   if(typeof ov[idx]!=='undefined') {
                       if(ov[idx].str!= item) {
                           changedText = item;
                           //changedIds.push(idx);
                       } else {changedText = '';}
                   } else {
                        //changedIds.push(idx);
                       changedText = item;
                   }

                   if(changedText!=='') {
                       self.quickAnalyse(changedText, idx);
                   }
               });
           },*/
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
               axios.post('/feedback', {'tap': this.tap, 'action': 'fetch'})
                   .then(response => {
                       this.feedback = response.data;
                   })
                   .catch(e => {
                       this.$data.errors.push(e)
                   });
           }
       }
   }
</script>