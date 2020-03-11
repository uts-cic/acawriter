<template>
<div>
    <div v-if="flash" class="alert alert-info alert-dismissible fade show" role="alert">
        {{flash}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col" v-on:click="sort('name')" v-bind:class="sortcol === 'name' ? 'sort sort-' + sortdir : 'sort'">Document name</th>
                <th scope="col" v-on:click="sort('genre')" v-bind:class="sortcol === 'genre' ? 'sort sort-' + sortdir : 'sort'">Genre</th>
                <th scope="col" v-on:click="sort('created')" v-bind:class="sortcol === 'created' ? 'sort sort-' + sortdir : 'sort'">Created</th>
                <th scope="col" v-on:click="sort('updated')" v-bind:class="sortcol === 'updated' ? 'sort sort-' + sortdir : 'sort'">Last updated</th>
                <th scope="col" v-on:click="sort('assignment')" v-bind:class="sortcol === 'assignment' ? 'sort sort-' + sortdir : 'sort'">Assignment</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for= "doc in documents" v-bind:id="'doc-' + doc.id">
                <th scope="row">
                    <a v-bind:href="'analyse/' + doc.slug" v-on:click="action('analyse', doc)">{{doc.name}}</a>
                </th>
                <td>{{doc.grammar}} ({{doc.feature_name}})</td>
                <td v-html="getCreated(doc.created_at)"></td>
                <td v-html="getLastUpdated(doc.draft_last_updated_at)"></td>
                <td v-html="getAssignment(doc.assignment)"></td>
                <td>
                    <a href="#" v-on:click="action('edit', doc)"><i class="fa fa-edit"></i></a> &nbsp;
                    <a href="#" v-on:click="action('delete', doc)"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        </tbody>
    </table>

    <edit-document></edit-document>
</div>
</template>

<script>
    import moment from 'moment';

    const COMPARE = {
        default: function(a, b) {
            if (a < b) {
                return -1;
            }
            if (a > b) {
                return 1;
            }
            return 0;
        },
        name: function(a, b) {
            return COMPARE.default(
                a.name,
                b.name
            );
        },
        genre: function(a, b) {
            return COMPARE.default(
                a.grammar + ' (' + a.feature_name + ')',
                b.grammar + ' (' + b.feature_name + ')'
            );
        },
        created: function(a, b) {
            console.log(a);
            return COMPARE.default(
                a.created_at,
                b.created_at
            );
        },
        updated: function(a, b) {
            return COMPARE.default(
                a.draft_last_updated_at,
                b.draft_last_updated_at
            );
        },
        assignment: function(a, b) {
            return COMPARE.default(
                a.assignment.name === 'NA' ? '-' : a.assignment.code,
                b.assignment.name === 'NA' ? '-' : b.assignment.code
            );
        },
    }
    export default {
        name: "document",
        data() {
            return {
                sortcol: 'updated',
                sortdir: 'desc',
                documents: [],
                errors: [],
                flash: ''
            }
        },
        mounted: function() {
            this.fetchDocuments();
        },
        methods: {
            sort(col) {
                if (col) {
                    this.$data.sortdir = this.$data.sortcol === col ? (this.$data.sortdir === 'asc' ? 'desc' : 'asc') : 'asc';
                    this.$data.sortcol = col;
                }
                this.$data.documents.sort(COMPARE[this.$data.sortcol]);
                if (this.$data.sortdir === 'desc') {
                    this.$data.documents.reverse();
                }
            },
            onDocumentUpdate(message) {
                this.$data.flash = message;
                setTimeout(() => {
                    this.$data.flash = '';
                }, 5000);
                this.fetchDocuments();
            },
            fetchDocuments() {
                axios.get('/documents').then((response) => {
                    this.$data.documents = response.data.documents;
                    this.sort();
                }, (err) => {
                    this.$data.errors.push(err)
                });
            },
            action(what, doc) {
                window.trackEvent('Document', what, doc.assignment.code);

                if (what === 'delete') {
                    if (confirm("All drafts and feedback associated with the document will be deleted. Do you wish to proceed?")) {
                        axios.post('/document/delete', { id: doc.id }).then((response) => {
                            this.$data.flash = response.data.message;
                            this.$data.documents = this.$data.documents.filter(d => d.id !== doc.id);
                            setTimeout(() => {
                                this.$data.flash = '';
                                //this.fetchDocuments();
                            }, 5000);
                        }, (err) => {
                            this.$data.errors.push(err)
                        });
                    }
                } else if (what === 'edit') {
                    let document = { id: doc.id, name: doc.name };
                    this.$modal.show('edit-document', { document: document, parent: this });
                }
            },
            getCreated(date) {
                return moment(date).format("DD/MM/YYYY HH:mm");
            },
            getLastUpdated(stamp) {
                return moment.unix(stamp).format("DD/MM/YYYY HH:mm");
            },
            getAssignment(assignment) {
                return !assignment || assignment.name === 'NA' ? '-' : assignment.code;
            }
        },
        computed: {}
    }
</script>
