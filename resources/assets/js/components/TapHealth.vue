<template>
    <section v-if="offline" class="tap-health">
        <div class="alert alert-danger">
            <i class="fa fa-warning"></i> Tap problem
        </div>
    </section>
    <section v-else>
        <div class="alert alert-success">
            <i class="fa fa-signal"></i> Tap Operational

        </div>
    </section>
</template>

<script>

    import moment from 'moment';
    var socket = io.connect('http://localhost:3000');

    export default {
        props: ['tapHealth'],
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
                console.log("called:" + this.tapHealth);
                if(this.tapHealth == 'Ok') {
                    this.offline = true;
                } else {
                    this.offline=false;
                }
            }
        },
        mounted() {

        }


    }

</script>