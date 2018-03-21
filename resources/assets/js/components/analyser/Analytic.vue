<template>
    <div>
        <!--<p><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></p>-->
       <!-- <h4>Analytical Feedback</h4> -->
    <ul class="nav nav-tabs bg-dark text-white">
        <li class="nav-item">
            <a class="nav-link active" href="#analysed" data-toggle="tab">Analytical Report</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#feedback" data-toggle="tab">Feedback</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#resources" data-toggle="tab">Resources</a>
        </li>
    </ul>
    <div class="tab-content ana activeClass" id="legend">
        <div class="tab-pane active" id="analysed" role="tabpanel">
             <div class="col-md-12 col-xs-12 bg-primary" v-for="rule in feedback.rules">
                 <template v-if="rule.tab==1">
                <h6 class="card-subtitle " v-if="rule.custom">{{rule.custom}}</h6>
                <ul class="list-inline">
                <template v-for="msg in rule.message">
                     <li class="list-inline-item" v-for="(m,id) in msg">
                        <!--<input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"> &nbsp;-->
                        <span v-bind:class="id"></span>&nbsp;<span v-html="m"></span>
                    </li>
                </template>
                </ul>
                 </template>
            </div>
            <div class="col-md-12">
                <span v-if="processing!==''"  class="text-success">
                    <i class="fa fa-spinner fa-spin"></i> {{processing}}
                    <span class="sr-only">Loading...</span>
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
                    <span v-html="feed.str" v-bind:class="[inLineAnaClasses(feed.css)]"
                          data-placement="top" data-toggle="tooltip" data-html="true"
                          v-bind:title="getTitle(feed.css)"
                          data-original-title="original">
                    </span>&nbsp;
                </span>
            </div>
        </div>
       <div class="tab-pane" id="feedback" role="tabpanel">
            <template v-if="feedback.tabs">
                <span v-for="(ref, idc) in feedback.tabs">
                    <template v-for="msg in ref">
                        <span v-for="feed in msg.customised">
                            <div class="alert alert-info" role="alert" v-for="fin_data in feed">{{fin_data}}</div>
                        </span>
                    </template>
                </span>
            </template>
        </div>
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
            },
            getTitle(css) {
                var outer= this;
                let title = '';
                css.forEach(function(g){
                    let a = outer.getAnnotation(g);
                    //console.log(a);
                    outer.feedback.rules.forEach(function(t) {
                        if(t.css.indexOf(a)!== -1) {
                            //console.log(t.custom);
                            title = t.custom ? t.custom:'Sorry nothing defined in the rule';
                        }
                    });
                });

                return title;
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