<template>
    <div id="editor">
        <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala>
        <br />
        <div v-model="preview">{{preview}}</div>
        <br />
        <hr />
        <h4>GraphQl data</h4>
        <div v-if="loading" class="loading">Loading .....</div>
        <!-- <article v-for="employee in viewer.employeeList">
             {{employee.firstName}} {{employee.lastName}}  ({{employee.birthDate}})
        </article>
        <h3>Products</h3>
        <ul>
            <li v-for="product in viewer.productList" class="list=item-group">
                {{product.name}} : ${{product.unitPrice}}
            </li>
        </ul>-->
        <ul v-if="errors && errors.length">
            <li v-for="error in errors">{{error.message}}</li>
        </ul>
        <h4>TAP Preview</h4>
        <div class="row">
            <span v-show="tapCall" class="fa fa-spinner fa-spin"></span>
            <div class="col-md-12" v-for="feed in tap">
                <div class="bg-danger">[{{feed.tags}}]</div>
                {{feed.str}}
           </div>

        </div>



   </div>
</template>

<script>
   import VueFroala from 'vue-froala-wysiwyg';
   Vue.use(VueFroala);

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
               errors:[],
               tapCall:true

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
           //console.log(this.editorContent);
       },
       watch :{
           editorContent () {

               this.editLog.push(this.editorContent);
               this.$data.tap='';
               this.$data.tapCall =true;
               axios.post('/processor', {'txt': this.editLog})
                   .then(response => {
                       this.$data.tap = response.data.responseTxt;
                       this.$data.tapCall=false;
                   })
                   .catch(e => {
                       this.$data.errors.push(e)
                   })
               ;


               //axios.post('http://athanor.utscic.edu.au/v2/analyse/text/rhetorical', {'data': this.editorContent})
//                     .then(r => console.log('r:', JSON.stringify(r,null,2)));
           }
   }


   }
</script>