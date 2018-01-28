<template>
    <div>
        <div v-if="flash!=''" class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{flash}}</strong>
        </div>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Faculty</th>
                <th scope="col">Genre</th>
                <th scope="col">Title</th>
                <th scope="col">Summary</th>
                <th>Feedback</th>
            </tr>
            </thead>
            <tbody>

            <tr v-for= "list in lists">
                <th scope="row">{{list.faculty}}</th>
                <td>{{list.feature.grammar}}</td>
                <td>{{list.title}}</td>
                <td><small>{{list.summary}}</small></td>
                <td><a v-bind:href="'example/analyse/'+list.id">View</a></td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

<script>

    export default {
        name: "exampleText",
        data() {
            return {
                lists: [],
                errors: [],
                flash:''

            }
        },
        mounted:function() {
            this.fetchExamples();
        },
        methods:{
            fetchExamples() {
                axios.get('/example/all').then((response) => {
                    this.$data.lists = response.data.examples;
                }, (err) => {
                    this.$data.errors.push(e)
                });
            }
           /* action(what, doc) {
                if(what === 'delete') {
                    let data = {'action': what, 'id': doc.id};
                    if (confirm("All feedback associated with the document will be deleted. Do you wish to proceed?")) {
                        axios.post('documents/action', data).then((response) => {
                            this.$data.flash = response.data.message;
                            setTimeout(() => {
                                this.$data.flash = '';
                                this.fetchDocuments();
                            }, 3000);
                        }, (err) => {
                            this.$data.errors.push(e)
                        });
                    }
                } else if(what === 'edit') {
                    let data = {'id':doc.id, 'name': doc.name };
                    this.$modal.show('edit-document', {'data':data} );
                }
            }*/
        },
        computed:{

        }
    }
</script>


