<template>
    <section v-if="offline" class="internet-connection">
        <div class="bg-danger">
            <span class="fa fa-warning"></span>
            <span>Internet connection lost</span>
        </div>
    </section>
    <section v-else>
        <div class="bg-default">
            <span class="fa fa-signal"></span> Last connection: {{lastHeartBeatReceivedAt}}

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