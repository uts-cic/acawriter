<template>
    <div v-bind:slogs="slogs">
        <strong>Online users <span class="badge">{{slogs.length}}</span></strong><br />
        <ul>
        <li class="list-group-item" v-for="d in slogs" >{{d.status}}</li>
        </ul>
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