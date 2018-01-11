<template>
    <div>
    <div v-if="flash!=''" class="alert alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{flash}}</strong>
    </div>
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Code</th>
            <th scope="col">Grammar</th>
            <th scope="col">Title</th>
            <th scope="col">Document</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        <tr v-for= "list in lists">
            <th scope="row">{{list.assignment.code}}</th>
            <td>Grammar</td>
            <td>{{list.assignment.name}}</td>
            <td><a v-bind:href="'analyse/'+list.assignment.code">{{list.slug}}</a></td>
            <td><a href="#" v-on:click="action('edit',list.id)"><i class="fa fa-edit"></i></a> &nbsp;
                <a href="#" v-on:click="action('delete',list.id)"><i class="fa fa-trash"></i></a>
            </td>
        </tr>

        </tbody>
    </table>
    </div>
</template>

<script>
    export default {
        name: "document",
        data() {
            return {
                lists: [],
                errors: [],
                flash:''

            }
        },
        mounted:function() {
            this.fetchDocuments();
        },
        methods:{
            fetchDocuments() {
                axios.get('/documents/all').then((response) => {
                    this.$data.lists = response.data.documents;
                }, (err) => {
                    this.$data.errors.push(e)
                });
            },
            action(what, idx) {
                let data ={'action': what, 'id':idx };
                if(confirm("All drafts associated with the Document will be deleted. Do you wish to proceed?")) {
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
            }
        },
        computed:{

        }
    }
</script>

