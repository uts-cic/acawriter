<template>
    <div>
        <p><small>Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar and plagiarism.</small></p>
        <h4>Reflective Feedback</h4>
        <!--<ul class="nav nav-tabs bg-dark text-white">
            <li class="nav-item">
                <a class="nav-link active" href="#analysed" data-toggle="tab">Feedback <small>(Reflective writing)</small></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#moreAna" data-toggle="tab">Extra</a>
            </li>
        </ul>
        <!-- <div class="tab-content ref activeClass" id="legend">
            <div class="tab-pane active" id="analysed" role="tabpanel">
                <div class="col-md-12 col-xs-12" v-for="rule in feedback.rules">
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
                </div>
            </div>
            <div class="tab-pane" id="moreAna" role="tabpanel">
                Some details here
            </div>
        </div> -->
        <div class="col-md-12">
            <span v-if="processing!=''" class="text-danger">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>{{processing}}
                    <span class="sr-only">Loading...</span>
                <hr />
            </span>
        </div>
        <div class="col-md-12 wrapper">
            <span v-for="(feed,idx) in feedback.final">
                <span v-for="ic in feed.css">
                    <template v-if="ic==='context' || ic==='challenge' || ic==='change' || ic==='metrics' || ic==='affect'">
                        <span v-bind:class="getIcons(ic)"></span>
                    </template>
                </span>
                <span v-html="inText(feed)" v-bind:class="[inLineClasses(feed.css)]"></span>
            </span>
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
                if(data.str!=='' && typeof data.expression!== 'undefined') {
                    let str = data.str;
                    if(data.expression.affect.length > 0) {
                        data.expression.affect.forEach(function(word) {
                            str = str.replace(word.text, "<span class='stdaffect affect'>"+word.text+"</span>");
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