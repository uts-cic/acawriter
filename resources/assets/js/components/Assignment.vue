<template>

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Code</th>
            <th scope="col">Title</th>
            <th scope="col">Grammar</th>
            <th scope="col">Description</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="assignment in assignmentList">
                <th scope="row">{{assignment.code}}</th>
                <td>{{assignment.name}}</td>
                <td><a href="/example" title="check out an example">{{assignment.feature.grammar}} <small>({{assignment.feature.name}})</small></a></td>
                <td><small>{{assignment.keywords}}</small></td>
                <td><a href="#" v-on:click="fetchAssignment(assignment)"><i class="fa fa-pencil-square-o"></i></a> &nbsp;
                    <a href="#" v-on:click="deleteAssignment(assignment)"><i class="fa fa-trash-o"></i></a>
                </td>

            </tr>
        </tbody>
    </table>

</template>

<script>
    export default {
        props: ['assignments'],
        data() {
            return {
                errors: [],
                status: {}
            }
        },
        methods: {
            fetchAssignment(ass) {

            },
            deleteAssignment(assignment) {
                var r = confirm("All drafts associated with the Assignment will be deleted. Are you sure?");
                if(r==true) {
                    let data = {'action': 'delete', 'id': assignment.id}
                    axios.post('/assignments/action', data).then((response) => {
                        response.data.status = this.$data.status;
                    }, (err) => {
                        this.$data.errors.push(e)
                    });
                }
            }
        },
        computed : {
            assignmentList() {
                if(this.assignments) {
                    return JSON.parse(this.assignments);
                } else {
                    return {};
                }
            }
        }

    }
</script>