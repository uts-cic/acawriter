<template>
    <li v-if="offline" class="list-group-item list-group-item-danger internet-connection">
        <i class="fa fa-warning"></i>&nbsp;<small>Internet connection lost</small>
    </li>
    <li v-else class="list-group-item list-group-item-success">
        <small>Online status last updated: {{formatedDate}}</small>
    </li>
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
        computed: {
            formatedDate() {
                return moment(String(this.lastHeartBeatReceivedAt)).format('MM/DD/YYYY hh:mm');
            }
        }


    }

</script>