<template>
    <section v-if="tapStatus=='failed'" class="tap-health">
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
                tapStatus:'failed',
            };
        },
        created() {
            setInterval(this.checkTapStatus, 1000);
        },
        methods: {
            checkTapStatus() {
                console.log("called:" + this.tapHealth);
                if(this.tapHealth == 'Ok') {
                    console.log("true");
                    this.tapStatus = 'ok';
                } else {
                    this.tapStatus='failed';
                }
            }
        },
        mounted() {

        }


    }

</script>