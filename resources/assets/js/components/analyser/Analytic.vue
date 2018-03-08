<template>
    <div>
        <p><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></p>
        <h4>Analytical Feedback</h4>
    <!--<ul class="nav nav-tabs bg-dark text-white">
        <li class="nav-item">
            <a class="nav-link active" href="#analysed" data-toggle="tab">Feedback <small>(Analytical writing)</small></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#moreAna" data-toggle="tab">Extra</a>
        </li>
    </ul>
    <div class="tab-content ana activeClass" id="legend">
        <div class="tab-pane active" id="analysed" role="tabpanel">
            <!-- <div class="col-md-12 col-xs-12" v-for="rule in feedback.rules">
                <h6 class="card-subtitle p-4" v-if="rule.custom">{{rule.custom}}</h6>
                <ul class="list-inline">
                <template v-for="msg in rule.message">
                     <li class="list-inline-item" v-for="(m,id) in msg">
                        <input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"> &nbsp;
                        <span v-bind:class="id"></span>&nbsp;<span v-html="m"></span>
                    </li>
                </template>
                </ul>
                <hr />
            </div> -->
            <div class="col-md-12">
                <span v-if="processing!==''"  class="text-success">
                    <i class="fa fa-spinner fa-spin"></i> {{processing}}
                    <span class="sr-only">Loading...</span>
                     <hr />
                </span>
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
                    <span v-html="feed.str" v-bind:class="[inLineAnaClasses(feed.css)]"></span>&nbsp;
                </span>
            </div>
        <!--</div>
       <div class="tab-pane" id="moreAna" role="tabpanel">
        Some details here
    </div>
 </div> -->
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
                    {'grow': 'T'},
                    {'contrast': 'C'},
                    {'contribution': 'S'},
                    {'nostat': 'Q'},
                    {'tempstat': 'B'},
                    {'attitude': 'P'},
                    {'novstat': 'N'},
                    {'surprise':'S'}

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