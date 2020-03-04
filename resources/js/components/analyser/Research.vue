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
                <div class="legend expanded">
                    <button v-on:click="toggleTags()" class="btn btn-link legend-button">{{showTags ? 'Hide' : 'Show'}} move details</button>
                    <template v-for="rule in feedback.rules">
                        <div v-if="rule.tab == 1 || !rule.tab" class="card card-moves" v-bind:class="[rule.name, showTags ? 'expanded' : 'collapsed']">
                            <div class="card-header" v-html="rule.custom"></div>
                            <transition name="slide-fade">
                                <div class="card-body" v-if="showTags">
                                    <ul class="rules-list" v-if="rule.tab == 1 || !rule.tab">
                                        <template v-for="msg in rule.message">
                                            <li v-for="(m,id) in msg" v-html="m"></li>
                                        </template>
                                    </ul>
                                </div>
                            </transition>
                        </div>
                    </template>
                </div>

                <hr>

                <div class="wrapper">
                    <template v-for="feed in feedback.final">
                        <br v-if="feed.str.match(/^<(br|p)/)">
                        <span v-bind:class="[getMove(feed)]">
                            <span class="nowrap" v-if="showTags">
                                <span v-for="ic in feed.css" class="badge" v-bind:class="'badge-' + getMove(feed, ic)" v-html="getAnnotation(ic)"></span>
                                <span v-html="getFirstWord(feed.str)"></span>
                            </span>
                            <span v-html="showTags ? getRemainingSentence(feed.str) : getPlainText(feed.str)"></span>
                        </span> <span class="spacer"></span>
                    </template>
                </div>
            </div>

            <template v-for="tab in vtabs">
                <div class="tab-pane" v-bind:id="getLowerCase(tab.tabName)" role="tabpanel">
                    <div v-for="(ref, idc) in feedback.tabs">
                        <template v-if="idc==tab.tab" v-for="msg in ref">
                            <span v-for="feed in msg">
                                <span v-for="a in feed">
                                    <div class="bd-callout bd-callout-info" v-for="b in a" v-html="b"></div>
                                </span>
                            </span>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <div v-else class="feedback-placeholder"></div>
</template>

<script>
    import store from '../../store';
    import { mapState, mapActions, mapGetters} from 'vuex';

    export default {
        name: "researchResult",
        store,
        data() {
            return {
                showTags: false,
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
            toggleTags() {
                this.showTags = !this.showTags;
            },
            getAnnotation(ic) {
                return this.analytic_xlator[ic] ? this.analytic_xlator[ic] : '';
            },
            getMove: function(data, tag) {
                for (let move of ['moves4', 'moves3', 'moves2', 'moves1']) {
                    let hasMove = data[move] && data[move].css && data[move].css.length;
                    if (!hasMove) {
                        continue;
                    }
                    if (!tag || data[move].css.indexOf(tag) >= 0) {
                        return move;
                    }
                }
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
            getPlainText(str) {
                let div = document.createElement('div');
                div.innerHTML = str;
                return div.innerText;
            },
            getFirstWord(str) {
                return this.getPlainText(str).replace(/[\s].*/, '');
            },
            getRemainingSentence(str) {
                return this.getPlainText(str).replace(/^[\S]*[\s]/, '');
            },
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
