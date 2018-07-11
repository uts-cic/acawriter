<template>
    <div>
        <!-- <div class="row pbar">
            <div class="col-md-12">
                <div class="progress" v-if="processing!==''">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 45%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div> -->
        <br />

        <ul class="nav nav-pills nav-fill bg-dark text-white">
            <li class="nav-item">
                <a class="nav-link active" href="#analysed" data-toggle="tab">Reflective Report</a>
            </li>
           <li class="nav-item">
                <a class="nav-link" href="#moreAna" data-toggle="tab">Feedback</a>
            </li>
        </ul>

         <div class="tab-content ref activeClass" id="legend">
            <div class="tab-pane active" id="analysed" role="tabpanel">
               <div class="bg-light ref_chk" v-for="rule in feedback.rules">
                    <h6 class="card-subtitle p-4" v-if="rule.custom">{{rule.custom}}</h6>
                    <ul class="list-inline">
                        <template v-for="msg in rule.message">
                            <li class="list-inline-item" v-for="(m,id) in msg">
                                <input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"> &nbsp;
                                <span v-bind:class="id"></span>&nbsp;<span v-html="m"></span>
                            </li>
                        </template>
                    </ul>
            </div>
                <hr />
            <div class="col-md-12 wrapper">
                <span v-for="(feed,idx) in feedback.final">
                    <span v-for="ic in feed.css">
                        <template v-if="ic==='context' || ic==='challenge' || ic==='change' || ic==='metrics' || ic==='affect'">
                            <span v-bind:class="getIcons(ic)"></span>
                        </template>
                    </span>
                    <span v-html="inText(feed)" v-bind:class="[inLineClasses(feed.css)]"></span>&nbsp;
                </span>
            </div>
            </div>
             <div class="tab-pane" id="moreAna" role="tabpanel">
                 <div class="alert alert-info"><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></div>
             </div>
         </div>

    </div>
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
                    if(data.expression.affect.length > 0 &&
                        ( _.includes(data.css, "context")  || _.includes(data.css, "challenge") || _.includes(data.css, "change")
                        )
                    ) {
                        data.expression.affect.forEach(function(word) {
                            if(!self.checkStopWords('affect', word.text)) {
                                str = str.replace(word.text, "<span class='stdaffect affect'>" + word.text + "</span>");
                            }
                        });

                    }
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
            }
        },
        computed:{
            ...mapGetters({
                feedback: 'currentFeedback',
                processing: 'loadingStatus'
            }),

        }
    }
</script>