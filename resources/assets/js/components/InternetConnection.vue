<template>
    <section v-if="offline" class="internet-connection">
        <div class="alert alert-danger">
            <i class="fa fa-warning"></i> Internet connection lost
        </div>
    </section>
    <section v-else>
        <div class="alert alert-success">
            <i class="fa fa-signal"></i> Last connection: {{lastHeartBeatReceivedAt}}

        </div>
    </section>
</template>

<script>

import moment from 'moment';
var socket = io.connect('http://localhost:3000');

export default {
    props: ['lastHeartBeatReceivedAt'],
    data() {
        return{
            offline:false,
        };
    },
    created() {
        setInterval(this.determineConnectionStatus, 1000);
    },
    methods: {
        determineConnectionStatus() {
            console.log("called:" + this.lastHeartBeatReceivedAt);
            const lastHeartBeatReceivedSecondsAgo = moment().diff(this.lastHeartBeatReceivedAt, 'seconds');
            this.offline = lastHeartBeatReceivedSecondsAgo > 125;
        }
    },
    mounted() {

    }


}

</script>