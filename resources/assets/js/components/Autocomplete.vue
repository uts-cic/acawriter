<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                    <ul>
                        <li v-for="error in errors">{{error.message}}</li>
                    </ul>
                </div>
                <div v-else-if="operation!==''" class="alert alert-info">{{operation}}
                    <a v-bind:href="'/analyse/' + this.link">Go to my document</a>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-md-4">
            <div v-bind:class="'open:openSuggestion'">
                Paste your assignment code
                <input type="text" placeholder="XT45EWS" v-model="query" v-on:keyup="autoComplete" class="form-control">
                <div class="panel-footer" v-if="results.length">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="(result,index) in results"
                            v-bind:class="{'active': isActive(index)}"
                            @click="suggestionClick(index)"
                        >
                            {{ result.name }} (<small>{{result.code}}</small>)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div v-if="selectedAssignments.length">

                <div  v-for="(a, i) in selectedAssignments" class="row">
                    <div class="col-md-12">Assignment Title: {{a.name}} ({{a.code}})</div>
                    <div class="col-md-12">Document name:<small class="text-danger">(ideally should be unique for self reference)</small>
                        <input type="text" class="form-control"  v-model="doc_name[i]"  /> &nbsp;
                        <button type="button" class="btn btn-dark" @click="addAssignments()"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add to My Documents</button>
                    </div>

                    <!-- <div class="col-md-1"><a href="#" @click="remove(i)"><i class="fa fa-minus-circle txt-danger"></i></a></div> -->
                </div>
                <hr />

            </div>

        </div>
    </div>


    </div>
</template>
<script>
    export default{
        data(){
            return {
                query: '',
                results: [],
                selectedAssignments:[],
                open:false,
                current:0,
                operation:'',
                doc_name:[],
               // doc_file:[],
                errors:[],
                link: '/analyse'

            }
        },
        methods: {
            autoComplete(){
                this.results = [];
                if(this.query.length > 7){
                    axios.get('/assignment/search',{params: {query: this.query}}).then(response => {
                        this.results = response.data;
                    });
                }
                this.open= true;
            },
            isActive(index) {
                return index === this.current;
            },
            suggestionClick(index) {
                var currentSelection = this.results[index];
                this.query = '';
                this.doc_name.push(currentSelection.name);
                //this.doc_file.push(currentSelection.name.replace(/\s+/g, '-').toLowerCase());
                currentSelection.doc_name= '';
                currentSelection.doc_file= '';
                this.selectedAssignments.push(currentSelection);
                this.open = false;
            },
            addAssignments() {
                let list = {};
                var self =this;
                list = self.selectedAssignments.map(function(assignment, idx) {
                    assignment.doc_name= self.$data.doc_name[idx];
                    assignment.doc_file= self.slug();
                    return assignment;
                });

                axios.post('/assignments/toUser', {'list': list})
                    .then(response => {
                        this.$data.operation = response.data.message;
                        this.selectedAssignments =[];
                        this.link = list[0].doc_file;
                        this.open =false;
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            },
            slug() {
                    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
            },
            remove(i) {
                this.selectedAssignments.splice(i,1);
                this.doc_name.splice(i,1);
                this.doc_file.splice(i,1);
            }
        },
        computed :{
            openSuggestion() {
                return this.query !== "" &&
                    this.results.length > 0 &&
                    this.open === true;
            }

        }
    }
</script>