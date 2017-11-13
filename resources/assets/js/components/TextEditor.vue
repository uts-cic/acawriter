<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Text Analyser
                        <button type="button" class="btn btn-outline-primary pull-right" v-on:click="fetchAnalysis()">Get Feedback</button>

                    </div>
                    <div class="card-body">
                        <div id="editor">
                            <textarea v-model="editorContent"  class="form-control"></textarea>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Operations</div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="fa fa-globe"></i> TAP <small>next updated after : {{10- counter}} changes.</small>
                        </div>
                        <div class="alert alert-success"><i class="fa fa-database" aria-hidden="true"></i> <small>Save: {{auto}} </small></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Features</div>
                    <div class="card-body">
                        <ul>
                            <li class="list-item-group">Vocabulary: <span class="badge badge-info">{{vocab}} </span></li>
                            <li class="list-item-group">Athanor</li>
                        </ul>
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
            <div class="col-md-10">
                <span v-show="tapCalls.athanor" class="fa fa-spinner fa-spin"></span>
                <span v-for="feed in tap">
                    [<span class="badge bg-primary">{{feed.tags}}</span>]
                    {{feed.str}}
                </span>

            </div>
            <div class="col-md-2"></div>
        </div>
    </div>

</template>

<script>

   import gql from 'graphql-tag';
   const POSTS_QUERY = gql`
   {
       viewer {
           employeeList {
               employeeID,
               firstName,
               lastName,
               birthDate
           },
           productList {
               name,
               unitPrice
           }
       }
   }
   `;

   export default {
       name: 'editor',
       props:['assignment'],
       data () {
           return {
               preview: '',
               editLog : [],
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
               quickTags:''
           }
       },
       apollo:{
           viewer: {
               query: POSTS_QUERY,
               loadingKey: 'loading'
           }
       },
       mounted () {
           console.log("mounted editor");
           console.log("lets prepopulate the first call" );
           this.editLog.push(this.editorContent);
           this.fetchAnalysis();
            console.log(this.assignment);
           //console.log(this.editorContent);
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

                    //newVal.replace(/<[^>]*>/g, '');
                    //console.log(newVal.split(/[\n\r]/g), this.$data.tap);
                    //var temp =  newVal.split(/[\n\r]/g);
                    //temp = temp.filter(entry => entry.trim() != '');
                    //console.log(temp);
                    //this.checkEligibility(newVal.split(/[\n\r]/g), this.$data.tap);
                   this.computeText(this.splitText, this.$data.tap);
                   this.editLog.push(this.editorContent);
                   this.$data.counter = 0;
               }
           }
        },
       methods: {
           fetchAnalysis() {
               console.log("into fetch");
              // this.$data.tap='';
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
                                   }
                               })
                               .catch(e => {
                                   this.$data.errors.push(e)
                               });

                       } else if(ov[idx].str == item) {
                           temp['str'] = ov[idx].str;
                           temp['tags'] = ov[idx].tags;
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
           /*quickAnalyse(changedText, idx) {

               this.$data.counter = 0;
               var tags = '';
               axios.post('/processor', {'txt': changedText, 'action': 'qathanor'})
                   .then(response => {
                       if(response.data) {
                           this.$data.tap[idx] = response.data.athanor;
                       }
                   })
                   .catch(e => {
                       this.$data.errors.push(e)
                   });

           },*/
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
           }
       }
   }
</script>