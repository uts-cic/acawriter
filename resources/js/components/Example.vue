<template>
    <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Document name</th>
                <th scope="col">Genre</th>
                <th scope="col">Faculty</th>
                <th scope="col">Summary</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="example in examples">
                <th scope="row"><a v-bind:href="'example/analyse/' + example.id">{{example.title}}</a></th>
                <td>{{example.feature.grammar}} ({{example.feature.name}})</td>
                <td>{{example.faculty}}</td>
                <td>{{example.summary}}</td>
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
                examples: []
            };
        },
        mounted: function() {
            this.fetchExamples();
        },
        methods:{
            fetchExamples() {
                axios.get('/example/all').then((response) => {
                    this.$data.examples = response.data.examples.sort(function(a, b) {
                        var nameA = (a.feature.grammar + a.feature.name).toUpperCase();
                        var nameB = (b.feature.grammar + b.feature.name).toUpperCase();
                        if (nameA < nameB) {
                            return -1;
                        }
                        if (nameA > nameB) {
                            return 1;
                        }
                        return 0;
                    });
                }, (err) => {});
            }
        }
    }
</script>
