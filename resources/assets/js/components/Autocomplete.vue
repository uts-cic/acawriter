<template>
    <div class="row">
        <div class="col-md-6">
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
        <div class="col-md-6">
            {{operation}}
            <div v-if="selectedAssignments.length">
                <div  v-for="(a, i) in selectedAssignments">
                    <small> {{a.name}} ({{a.code}})
                        &nbsp;<a href="#" @click="remove(i)"><i class="fa fa-minus-circle txt-danger"></i></a>
                    </small>
                </div>
                <button type="button" class="btn btn-success btn-sm" @click="addAssignments()"><i class="fa fa-plus-square-o"></i> Add</button>
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
                operation:''
            }
        },
        methods: {
            autoComplete(){
                this.results = [];
                if(this.query.length > 2){
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
                this.selectedAssignments.push(currentSelection);
                this.open = false;
            },
            addAssignments() {
                axios.post('/assignments/toUser', {'list': this.selectedAssignments})
                    .then(response => {
                        this.$data.operation = response.data.message;
                        this.selectedAssignments =[];

                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
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