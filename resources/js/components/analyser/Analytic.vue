<template>
    <div v-if="feedback.rules">
        <ul class="nav nav-tabs nav-fill awa-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#analysed" data-toggle="tab" data-ga-action="tab:report">Analytical Report</a>
            </li>
            <template v-for="tab in vtabs">
                <li class="nav-item">
                    <a class="nav-link" v-bind:href="'#'+getLowerCase(tab.tabName)" data-toggle="tab" v-bind:data-ga-action="'tab:' + getLowerCase(tab.tabName)">{{tab.tabName}}</a>
                </li>
            </template>
        </ul>

        <div class="tab-content ana" id="legend">
            <div class="tab-pane active" id="analysed" role="tabpanel">
                <template v-for="rule in feedback.rules">
                    <div v-if="rule.tab==1 || !rule.tab">
                        <span class="card-subtitle" v-if="rule.custom" v-html="rule.custom"></span>
                        <ul class="rules-list" v-bind:class="rule.name">
                            <template v-for="msg in rule.message">
                                <li v-for="(m,id) in msg" v-html="m"></li>
                                <!--<input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"> &nbsp;-->
                                <!--<span v-bind:class="id"></span>-->
                            </template>
                        </ul>
                    </div>
                </template>

                <hr>

                <div class="wrapper">
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
            </div>

            <div class="tab-pane" v-bind:id="getLowerCase(tab.tabName)" role="tabpanel" v-for="tab in vtabs">
                <template v-for="(ref, idc) in feedback.tabs">
                    <template v-if="idc==tab.tab" v-for="msg in ref">
                        <template v-for="feed in msg">
                            <template v-for="a in feed">
                                <div class="feedback-item" v-for="b in a" v-html="b"></div>
                            </template>
                        </template>
                    </template>
                </template>
            </div>
        </div>
    </div>
    <div v-else class="feedback-placeholder"></div>
</template>

<script>
    import store from '../../store';
    import { mapState, mapActions, mapGetters} from 'vuex';

    export default {
        name: "analyticResult",
        store,
        data() {
            return {
                analytic_xlator: {
                    metrics: 'metrics',
                    emph: 'E',
                    grow: 'T',
                    contrast: 'C',
                    contribution: 'S',
                    nostat: 'Q',
                    tempstat: 'B',
                    attitude: 'P',
                    novstat: 'N',
                    surprise: 'S'
                }
            }
        },
        methods:{
            getAnnotation(ic) {
                let tg = typeof this.analytic_xlator[ic]!=='undefined' ? this.analytic_xlator[ic] : '';
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
            },
            getRuleName(tag) {
                let tabs = [];
                let mv = [];
                let name ='';
                let rules = this.feedback.rules;

                tabs = rules.filter(rule => rule.tab  == 1);

                tabs.forEach(function(item) {
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
