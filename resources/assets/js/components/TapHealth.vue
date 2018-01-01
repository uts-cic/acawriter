<template>
    <li v-if="tapStatus=='failed'" class="list-group-item list-group-item-danger tap-health">
        <i class="fa fa-warning"></i> Tap API connection problem
    </li>
    <li v-else class="list-group-item list-group-item-success">
        <i class="fa fa-signal"></i> Tap Operational
    </li>
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