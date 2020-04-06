<template>
    <div v-if="vtabs">
        <ul class="nav nav-tabs nav-fill awa-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#analysed" data-toggle="tab" data-ga-action="tab:report">Reflective Report</a>
            </li>
            <template v-for="tab in vtabs">
                <li class="nav-item">
                    <a class="nav-link" v-bind:href="'#'+getLowerCase(tab.tabName)" data-toggle="tab" v-bind:data-ga-action="'tab:' + getLowerCase(tab.tabName)">{{tab.tabName}}</a>
                </li>
            </template>
        </ul>

        <div class="tab-content ref" id="legend">
            <div class="tab-pane active" id="analysed" role="tabpanel">
                <template v-for="rule in feedback.rules">
                    <div class="ref_chk" v-if="rule.tab==1 || !rule.tab">
                        <span class="card-subtitle" v-if="rule.custom" v-html="rule.custom"></span>
                        <ul class="list-unstyled">
                            <template v-for="msg in rule.message">
                                <li v-for="(m,id) in msg">
                                    <input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"> &nbsp;
                                    <span v-bind:class="id"></span>&nbsp;<span v-html="m"></span>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <hr>

                <div class="wrapper">
                    <span v-for="(feed,idx) in feedback.final">
                        <span v-for="ic in feed.css">
                            <!-- AI/2019-06-25: Removing affect analysis -->
                            <!-- <template v-if="ic==='context' || ic==='challenge' || ic==='change' || ic==='metrics' || ic==='affect'"> -->
                            <template v-if="ic==='context' || ic==='challenge' || ic==='change' || ic==='metrics'">
                                <span v-bind:class="getIcons(ic)"></span>
                            </template>
                        </span>
                        <span v-html="inText(feed)" v-bind:class="[inLineClasses(feed.css)]"></span>&nbsp;
                    </span>
                </div>
            </div>

            <div class="tab-pane" v-bind:id="getLowerCase(tab.tabName)" role="tabpanel" v-for="tab in vtabs">
                <template v-for="(ref, idc) in feedback.tabs">
                    <template v-if="idc == tab.tab" v-for="(msg, idm) in ref">
                        <div class="feedback-item" v-for="feed in msg">
                            <template v-for="a in feed">
                                <ul class="list-unstyled">
                                    <li class="feedback-list-item" v-for="b in a" v-html="b"></li>
                                </ul>
                            </template>
                        </div>
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
        name: "reflectiveResult",
        //props: ['feed','processing'],
        store,
        data() {
            return {
                customRules:[]
            }
        },
        mounted:function() {
        },
        methods:{
            getIcons(ic) {
                return 'std'+ic+' '+ic;
            },
            inLineClasses: function(data) {
                var temp=  data.filter(function( obj ) {
                    if (obj ==='link2me') {
                        return obj + ' std' + obj;
                    }
                });
                return temp;
            },
            inText: function(data) {

                var self = this;
                //console.log(words);
                if(data.str!=='' && typeof data.expression!== 'undefined') {
                    let str = data.str;
                    // AI/2019-06-25: Removing affect analysis
                    // if(data.expression.affect.length > 0 &&
                    //     ( _.includes(data.css, "context")  || _.includes(data.css, "challenge") || _.includes(data.css, "change")
                    //     )
                    // ) {
                    //     data.expression.affect.forEach(function(word) {
                    //         if(!self.checkStopWords('affect', word.text)) {
                    //             str = str.replace(word.text, "<span class='stdaffect affect'>" + word.text + "</span>");
                    //         }
                    //     });

                    // }
                    if(data.expression.epistemic.length > 0) {
                        data.expression.epistemic.forEach(function(word) {
                            str = str.replace(word.text, "<span class='stdepistemic epistemic'>"+word.text+"</span>");
                        });
                    }
                    if(data.expression.modal.length > 0) {
                        data.expression.modal.forEach(function(word) {
                            str = str.replace(word.text, "<span class='stdmodal modall'>"+word.text+"</span>");
                        });
                    }
                    return str;


                } else {
                    let str = '';
                    return str;
                }
            },
            checkStopWords:function(tag, word) {
                let words = [];
                let result = false;
                if(this.feedback.rules) {
                    words = this.feedback.rules.filter((item) => item.name==='filterWords')
                        .map((item) => item.check.list);
                }

                if(words.length > 0) {
                    words.forEach(function(v) {
                        if(_.includes(v.tags, tag) && _.includes(v.words, word)) {
                            return true;
                        }
                    });
                }
                return result;
            },
            getLowerCase(str) {
                let ch= str.replace(' ', '-');
                return ch.toLowerCase();
            },
            getFeedbackHeading(idm) {
                //to sort out haven't had a chance to do so
                let heading ='';
                return heading;
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
    }
</script>
