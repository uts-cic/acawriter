<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6"><h3 v-if="preSetAssignment">{{preSetAssignment.name}}</h3></div>
            <div class="col-md-4 text-success">
                <span v-if="draftUpdate.message!=''">{{draftUpdate.message}}</span>
            </div>
        </div>
        <div v-if="admin" class="row">
            <div class="col-md-12">
                <div class="card bg-secondary text-white">
                    <div class="card-block p-3">
                        <div class="row">
                            <div class="col-md-4"><label for="faculty">Faculty</label><input class="form-control" type="text" id="faculty" v-model="example.faculty" /></div>
                            <div class="col-md-4"><label for="faculty">Title</label><input class="form-control" type="text" id="title" v-model="example.title" /></div>
                            <div class="col-md-4"><label for="genre">Genre</label>
                                <select class="form-control" id="genre" v-model="example.genre">
                                <option value="">Select</option>
                                <option value="2">Reflective</option>
                                <option value="1">Analytic</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><label for="summary">Summary</label><input class="form-control" type="text" v-model="example.summary" id="summary" /></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div v-if="errors && errors.length" class="col-md-12 alert alert-danger" role="alert">
                    <ul>
                        <li v-for="error in errors">{{error.message}}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row editWrapper">
            <div id="sidebar" class="active" v-bind:class="this.attributes.grammar == 'analytic'? 'ana' : 'ref'">
                <div class="p-3 bg-uts-primary text-white"><i class="fa fa-info-circle" aria-hidden="true"></i> Key
                    <i class="fa fa-times-circle pull-right" aria-hidden="true" id="sidebarCollapseTwice"></i>
                </div>
                <div class="col-md-12 col-xs-12" v-for="rule in feedback.rules">
                    <h6 class="card-subtitle mb-2">&nbsp;</h6>
                    <div v-for="msg in rule.message">
                        <div class="row" v-for="(m,id) in msg">
                            <div class="col-md-1"><input type="checkbox" v-bind:id="id" v-bind:value="id" checked="checked"></div>
                            <div class="col-md-10"><span v-bind:class="id"></span>&nbsp;<span v-html="m"></span></div>
                        </div>
                    </div>
                    <hr />
                </div>
            </div>


            <!-- start content -->
            <div id="content" class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="row">
                            <div class="col-md-2">Example Text Analyser</div>
                            <div class="col-md-2 text-right">
                                <span class="text-white" v-if="auto!=''"><small>{{auto}}</small></span>
                            </div>
                            <div class="col-md-8">
                                <div class="btn-group pull-right" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-primary" v-on:click="fetchFeedback()"><i class="fa fa-cloud-download"  aria-hidden="true"></i> Get Feedback</button>&nbsp;
                                    <button type="button" class="btn btn-primary btn-sm" v-if="admin" v-on:click="storeAnalysedDrafts()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>&nbsp;
                                    <button type="button" id="sidebarCollapse" class="btn btn-primary"><i class="fa fa-info-circle" aria-hidden="true"></i> Key</button>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="editor">
                                    <!-- <froala :tag="'textarea'" :config="config" v-model="editorContent"></froala> -->
                                    <vue-editor v-model="editorContent" :editorToolbar="customToolbar"></vue-editor>
                                </div>
                            </div>
                            <!--- Reflective feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'reflective'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'reflective'">

                                <div class="col-md-12"><h4>Feedback <small>(Reflective)</small></h4>
                                    <span v-if="processing!=''" class="text-danger">
                                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>{{processing}}
                                        <span class="sr-only">Loading...</span>
                                    </span>
                                    <hr /></div>
                                <div class="col-md-12 wrapper">
                                    <!--<span v-html="editorContent"></span>-->

                                    <span v-for="(feed,idx) in feedback.final">
                                        <span v-for="ic in feed.css">
                                            <template v-if="ic==='context' || ic==='challenge' || ic==='change' || ic==='metrics' || ic==='affect'">
                                                <span v-bind:class="getI(ic)"></span>
                                            </template>
                                        </span>
                                        <!-- &nbsp;<span v-html="feed.str" v-bind:class="[inLineClasses(feed.css)]"></span> -->
                                        <span v-html="inText(feed)" v-bind:class="[inLineClasses(feed.css)]"></span>

                                    </span>
                                </div>
                            </div>
                            <!-- end of reflective -->


                            <!--- Analytic feedback --->
                            <div class="col-md-6 bg-light" v-bind:class="this.attributes.grammar == 'analytic'? 'activeClass' : 'nonactive'" v-if="this.attributes.grammar == 'analytic'">
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
                                            <template v-else-if="ic=='metrics'">
                                                <span v-bind:class="ic"></span>
                                            </template>
                                            <template v-else>
                                                <span class="badge badge-pill badge-analytic" v-bind:class="ic" v-html="getAna(ic)"></span>
                                            </template>
                                        </span>
                                        <span v-html="feed.str" v-bind:class="[inLineAnaClasses(feed.css)]"></span>

                                    </span>
                                </div>
                            </div>
                            <!-- end of analytics -->

                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</template>

<script>
    /**
     commented out - license needed
     import VueFroala from 'vue-froala-wysiwyg';
     Vue.use(VueFroala);
     *
     */
    const EventBus = new Vue();
    import { VueEditor } from 'vue2-editor';
    import moment from 'moment';
    import store from '../store';
    import { mapState, mapActions, mapGetters} from 'vuex';

    export default {
        components: {
            VueEditor
        },
        name: 'editor',
        props:['ex', 'role'],
        store,
        data () {
            return {
                editorContent: 'Edit Your Content Here!',
                loading: 0,
                tap:[],
                errors:[],
                counter:0,
                tempIds:[],
                auto:'',
                autosave:'',
                example:{
                    faculty:'',
                    title:'',
                    summary:'',
                    genre:1
                },
                splitText:[],
                quickTags:'',
                customToolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ],
                cssSpec: {
                    inline :['link2me'],
                    iconic :['context', 'challenge', 'change', 'metrics', 'affect'],
                    inText :['affect', 'epistemic','modall']
                },
                analytic_xlator:[
                    {'metrics': 'metrics'},
                    {'emph': 'E'},
                    {'vis': 'T'},
                    {'contrast': 'C'},
                    {'contribution': 'S'},
                    {'novstat': 'N'},
                    {'tempstat': 'B'},
                    {'attitude': 'P'},
                ],
                initFeedback:true
            }
        },
        mounted () {
            if(this.initFeedback) {
                this.fetchFeedback();
            }
        },
        created() {
            this.auto = '';
            //setInterval(this.storeAnalysedDrafts, 900000);
            // setInterval(this.quickCheck, 300000);
        },
        computed: {
            reflective: function() {
                return this.attributes.feedbackOpt == 'reflective' ? 'display:inline': '';
            },
            analytic: function() {
                return this.attributes.feedbackOpt == 'analytic' ? 'display:inline': '';
            },
            ...mapGetters({
                feedback: 'currentFeedback',
                processing: 'loadingStatus'
            }),
            admin: function() {
                return this.role;
            },
            preSetAssignment: function() {
                if(this.ex) {
                    return JSON.parse(this.ex);
                } else {
                    return false;
                }
            },
            attributes: function() {
                if(this.preSetAssignment) {
                    if(this.preSetAssignment.raw_response) {
                        this.editorContent = this.preSetAssignment.text_input;

                        let data = {'savedFeed':JSON.parse(this.preSetAssignment.raw_response)};
                        this.$store.dispatch('PRELOAD_FEEDBACK',data);
                        this.initFeedback = false;
                    }
                    let feature = this.preSetAssignment.feature;
                    return {
                        feedbackOpt:feature.grammar.toLowerCase() == 'analytic' ? 'a_01': 'r_01',
                        grammar: feature.grammar.toLocaleLowerCase(),
                        feature: feature.id
                    };
                } else {
                    return {
                        feedbackOpt:this.example.genre== 1 ? 'a_01' : 'r_01',
                        grammar: this.example.genre == 1 ? 'analytic' : 'reflective',
                        feature:this.example.genre
                    };
                }
                //setInterval(this.storeAnalysedDrafts('auto'), 900000);
            },
            rulesClasses: function() {
                let rules = [];
                rules = this.feedback.rules.map(function(rule,idx){
                    return rule.css.map(function(cl){
                        return cl;
                    });
                });
                let classes = [].concat(...rules);
                return classes;
            },
            draftUpdate: function() {
                let upd = {};
                upd.message ='';
                var s = this;
                if(this.userActivity && this.preSetAssignment){
                    //console.log(this.slogs.details.status);
                    this.userActivity.forEach(function(activity){
                        if(activity.data) {
                            if(activity.data.type==='Draft') {
                                upd.message = "Draft Saved " + moment().format('DD/MM/YYYY hh:mma');
                                s.auto = "Draft Saved " + moment().format('DD/MM/YYYY hh:mma');
                            }
                        }
                    });
                }

                return upd;
            },
        },
        watch :{
        },
        methods: {
            fetchFeedback() {
                this.errors=[];
                //this.autoCheck = true;
                if(this.feedbackOpt!=='') {
                    // let data = {'tap': this.tap, 'txt':'', 'action': 'fetch', 'extra': this.attributes};
                    let data = {'txt':this.editorContent, 'action': 'fetch', 'extra': this.attributes};
                    this.$store.dispatch('LOAD_FEEDBACK',data);
                    //this.autoCheck = false;
                } else {
                    this.$data.errors.push({'message':'Please select feedback type'});
                }
            },
            getI(ic) {
                return 'std'+ic+' '+ic;
            },
            getAna(ic) {
                let tg = '';
                this.analytic_xlator.forEach(function(val){
                    if(val[ic]) {
                        // console.log(val[ic]);
                        tg = val[ic];
                    }
                });
                return tg;
            },
            inLineClasses: function(data) {
                var temp=  data.filter(function( obj ) {
                    if (obj ==='link2me') {
                        return obj + ' std' + obj;
                    }
                });
                return temp;
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
            },
            storeAnalysedDrafts() {
                this.$data.auto='processing....';
                let data = {'txt':this.editorContent, 'action': 'store', 'extra': this.attributes,'feedback':this.feedback, 'other':this.example};
                axios.post('/example/store', data)
                    .then(response => {
                        this.$data.auto = 'Stored : '+ moment().format('DD/MM/YYYY hh:mma');
                    })
                    .catch(e => {
                        this.$data.errors.push(e)
                    });
            }

        }
    }
</script>