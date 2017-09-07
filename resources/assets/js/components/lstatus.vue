<template>
    <div>

        <li class="list-group-item" v-for="d in slogs" v-bind:slogs="slogs">{{d.status}}</li>
    </div>

</template>

<script>
    export default {
        props: ['slogs'],
        data() {
            return {
                other: [
                    {
                        "status": "Info from log",
                        "info": "test test test",
                        "time": "test"
                    }
                ]
            };
        }

    }
</script>