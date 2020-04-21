<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Code</th>
                <th scope="col">Title</th>
                <th scope="col">Genre</th>
                <th scope="col">Description</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="assignment in assignmentList">
                <th scope="row">{{assignment.code}}</th>
                <td>{{assignment.name}}</td>
                <td>{{assignment.feature.grammar}} <small>({{assignment.feature.name}})</small></td>
                <td>{{assignment.keywords}}</td>
                <td>
                    <!-- <a href="#" v-on:click="fetchAssignment(assignment)"><i class="fa fa-pencil-square-o"></i></a> &nbsp; -->
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
                var r = confirm("Students will no longer be able to use this assignment code. Are you sure?");
                if (r) {
                    let data = { 'id': assignment.id }
                    axios.post('/assignment/delete', data).then((response) => {
                        response.data.status = this.$data.status;
                        let assignments = JSON.parse(this.assignments);
                        this.assignments = JSON.stringify(assignments.filter(a => a.id !== assignment.id));
                    }, (err) => {
                        this.$data.errors.push(e);
                    });
                }
            }
        },
        computed : {
            assignmentList() {
                return this.assignments ? JSON.parse(this.assignments) : [];
            }
        }
    }
</script>
