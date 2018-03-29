<template>
    <div>
        <!--<p><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></p>-->
       <!-- <h4>Analytical Feedback</h4> -->
    <ul class="nav nav-pills nav-fill bg-dark text-white">
        <li class="nav-item">
            <a class="nav-link active" href="#analysed" data-toggle="tab">Analytical Report</a>
        </li>
        <template v-for="tab in vtabs">
            <li class="nav-item">
                <a class="nav-link" v-bind:href="'#'+getLowerCase(tab.tabName)" data-toggle="tab">{{tab.tabName}}</a>
            </li>
        </template>
    </ul>
    <div class="tab-content ana activeClass" id="legend">
        <div class="tab-pane active p-1" id="analysed" role="tabpanel">
            <div class="col-md-12 col-xs-12" v-for="rule in feedback.rules">
                <template v-if="rule.tab==1 || !rule.tab">
                    <h5 class="card-subtitle " v-if="rule.custom">{{rule.custom}}</h5>
                    <ul class="list-inline p-2" v-bind:class="rule.name">
                        <template v-for="msg in rule.message">
                            <li class="list-inline-item" v-for="(m,id) in msg">
                            <!--<input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"> &nbsp;-->
                            <span v-bind:class="id"></span>&nbsp;<span v-html="m"></span>
                            </li>
                        </template>
                    </ul>
                </template>
            </div>
            <hr />
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
        <template v-for="tab in vtabs">
            <div class="tab-pane" v-bind:id="getLowerCase(tab.tabName)" role="tabpanel">
               <span v-for="(ref, idc) in feedback.tabs">
                   <template v-if="idc==tab.tab" v-for="msg in ref">
                       <span v-for="feed in msg">
                           <span v-for="a in feed">
                               <div class="col-md-12 p-2" v-for="b in a" v-html="b"></div>
                           </span>
                       </span>
                   </template>
               </span>



              <!-- <div class="col-md-12 p-2">
                   <div class="alert alert-success" role="alert">
                       Thank you for submitting your draft to AcaWriter.Quality writing comes from revision. Research shows that writing drafts and revising your text helps improve the quality of your writing.
                   </div>
                   <div class="alert alert-danger"><small>Remember AcaWriter is a machine – so it may not highlight all your moves correctly and could give you incorrect feedback. So, don’t be afraid to disagree with the feedback, if you believe you have included all three moves in the correct order.</small> </div>

               </div> -->
            </div>


        </template>
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
                let rname = '';
                var out =this;
                data.forEach(function( obj ) {
                    //let a = outer.getAnnotation(g);
                    /** all this hack jus tto harcode moves bg colors!!!! **/
                    rname  = out.getRuleName(obj);
                    if(rname !== '') {
                        temp = rname;
                    } else {
                        if (obj === 'contribution') {
                            temp = 'ana_bg_green';
                        } else if (obj != 'metrics') {
                            temp = 'ana_bg_yellow';
                        }
                    }
                });
                return temp;
            },
            getTitle(css) {
                var outer= this;
                let title = '';
                css.forEach(function(g){
                    let a = outer.getAnnotation(g);
                    let tab = 1;
                    //console.log(a);
                    outer.feedback.rules.forEach(function(t) {
                        if(typeof t.tab !== 'undefined') tab = t.tab;
                        if(t.css.indexOf(a)!== -1 && tab === 1) {
                            //console.log(t.custom);
                            title = t.custom ? t.custom:'Sorry nothing defined in the rule';
                        }
                    });
                });

                return title;
            },
            getLowerCase(str) {
                return str.toLowerCase();
            }
            ,getRuleName(tag) {
                let tabs = [];
                let mv = [];
                let name ='';
                let rules = this.feedback.rules;

                tabs = rules.filter(rule => rule.tab  == 1);
                console.log(tabs);
                tabs.forEach(function(item) {
                    console.log(item);
                    console.log(item.check.tags);
                    if(item.check.tags.indexOf(tag)!== -1) mv.push(item.name);
                });
                //console.log(mv);
                if(mv.indexOf('moves3')!== -1) name = 'moves3';
                else if(mv.indexOf('moves2')!== -1) name = 'moves2';
                else if(mv.indexOf('moves1')!== -1) name = 'moves1';
                else name ='';

                return name;
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
            vtabs() {
                if(this.feedback.rules) {
                    let tabs = [];
                    let rtabs = [];
                    let rules = this.feedback.rules;
                    tabs = rules.filter(rule => rule.tab  > 1);
                    let curr = 0;
                    tabs.forEach(function(item) {
                        if(curr != item.tab)  rtabs.push(item);
                        curr = item.tab;
                    });
                    return rtabs;

                }
            }

        }
        /*watch: {
            feed: function(val, oldVal) {
                return this.feed;
            }
        }*/
    }
</script>