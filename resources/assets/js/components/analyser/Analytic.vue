<template>
    <div>
        <div class="col-md-12"><h4>Feedback <small>(Analytical)</small></h4>
            <span v-if="processing!==''"  class="text-success">
                <i class="fa fa-spinner fa-spin"></i> {{processing}}
                <span class="sr-only">Loading...</span>
            </span>
            <hr />
        </div>
        <div class="col-md-12 wrapper">
            <span v-for="(feed,idx) in feedback.final">
                <span v-for="ic in feed.css">
                    <template v-if="ic=='contribution'">
                        <span class="badge badge-pill badge-analytic-green" v-bind:class="ic">S</span>
                    </template>
                    <template v-else-if="ic=='metrics' || ic=='background'">
                        <span v-bind:class="ic"></span>
                    </template>
                    <template v-else>
                        <span class="badge badge-pill badge-analytic" v-bind:class="ic" v-html="getAnnotation(ic)"></span>
                    </template>
                </span>
                <span v-html="feed.str" v-bind:class="[inLineAnaClasses(feed.css)]"></span>
            </span>
        </div>
    </div>
</template>

<script>

    import store from '../../store';
    import { mapState, mapActions, mapGetters} from 'vuex';


    export default {
        name: "analyticResult",
        //props: ['feed','processing'],
        store,
        data() {
            return {
                analytic_xlator:[
                    {'metrics': 'metrics'},
                    {'emph': 'E'},
                    {'vis': 'T'},
                    {'contrast': 'C'},
                    {'contribution': 'S'},
                    {'nostat': 'N'},
                    {'tempstat': 'B'},
                    {'attitude': 'P'},
                ],
            }
        },
        mounted:function() {
        },
        methods:{
            getAnnotation(ic) {
                let tg = '';
                this.analytic_xlator.forEach(function(val){
                    if(val[ic]) {
                        tg = val[ic];
                    }
                });
                return tg;
            },
            inLineAnaClasses: function(data) {
                var temp=  '';
                data.forEach(function( obj ) {
                    if (obj ==='contribution') {
                        temp = 'ana_bg_green';
                    } else if(obj != 'metrics') {
                        temp = 'ana_bg_yellow';
                    }
                });
                return temp;
            }
        },
        computed:{
            /*feedback() {
                return this.feed;
            }*/
            ...mapGetters({
                feedback: 'currentFeedback',
                processing: 'loadingStatus'
            }),

        }
        /*watch: {
            feed: function(val, oldVal) {
                return this.feed;
            }
        }*/
    }
</script>