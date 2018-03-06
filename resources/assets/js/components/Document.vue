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
            <th scope="col">Genre</th>
            <th scope="col">Assignment Title</th>
            <th scope="col">Document Title</th>
            <th scope="col">Last Updated</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        <tr v-for= "list in lists">
            <th scope="row">{{list.assignment.code}}</th>
            <td><a href="/example" title="check out an example">{{list.grammar}}<small>({{list.feature_name}})</small></a></td>
            <td>{{list.assignment.name}}</td>
            <!-- <td><a v-bind:href="'analyse/'+list.assignment.code">{{list.name}}</a></td> -->
            <td><a v-bind:href="'analyse/'+list.slug">{{list.name}}</a></td>
            <td><span v-if="list.draft_last_updated_at" v-html="getLastUpdated(list.draft_last_updated_at)"></span></td>
            <td><a href="#" v-on:click="action('edit',list)"><i class="fa fa-edit"></i></a> &nbsp;
                <a href="#" v-on:click="action('delete',list)"><i class="fa fa-trash"></i></a>
            </td>
        </tr>

        </tbody>
    </table>
        <edit-document></edit-document>
    </div>
</template>

<script>
    import moment from 'moment';
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
            action(what, doc) {
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
            },
            getLastUpdated(stamp) {
                return moment.unix(stamp).format("DD-MM-YYYY HH:MM:SS");
            }
        },
        computed:{

        }
    }
</script>

