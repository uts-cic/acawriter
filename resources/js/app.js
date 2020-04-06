/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

//import modals lib
import VModal from 'vue-js-modal';

Vue.use(VModal);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('form-new', require('./components/forms/FormNew.vue').default);
Vue.component('form-subscribe', require('./components/forms/FormSubscribe.vue').default);
Vue.component('doc-editor', require('./components/TextAnalyser.vue').default);
Vue.component('internet-connection', require('./components/InternetConnection.vue').default);
Vue.component('tap-status', require('./components/TapHealth.vue').default);
Vue.component('assignment-list', require('./components/Assignment.vue').default);
Vue.component('documents', require('./components/Document.vue').default);
Vue.component('edit-document', require('./components/modal/EditDocument.vue').default);
Vue.component('example-text', require('./components/Example.vue').default);
Vue.component('ex-doc-editor', require('./components/ExampleAnalyser.vue').default);

var socket = io.connect(window.location.protocol + '//' + window.location.hostname);

import moment from 'moment';
const app = new Vue({
    el: '#app',
    data: {
        slogs: [],
        lastHeartBeatReceivedAt: moment(),
        tapHealth: 'failed',
        userActivity:[]
    },
    mounted: function() {

        socket.on('operational-log:App\\Events\\OperationLog', function(data){
            this.$data.slogs.push(data.details);
            return this.$data.slogs;
        }.bind(this));

        socket.on('private-dashboard:App\\Events\\InternetConnection\\Heartbeat', function () {
  //          console.log("ok yes listened");
            return this.lastHeartBeatReceivedAt = moment().format('LLL');
        }.bind(this));

        socket.on('private-dashboard:App\\Events\\Tap\\Health', function (data) {
//            console.log("ok yes listened tap health");
            return this.tapHealth=data.health.message;
        }.bind(this));

        socket.on('private-user-activity:App\\Events\\UserActivity', function (data, dd) {
            //console.log(data);
            return this.userActivity.push(data);
        }.bind(this));

    },
    methods: {
        processLog (txt) {
            console.log(txt);
        }
    }
});

window.trackEvent = function (category, action, label) {
    var params = {
        event_category: category,
        event_label: label
    };
    if (ROLE) {
        params.role = ROLE;
    }
    // console.log(params);
    // alert([category, action, label].join(', '));
    gtag('event', action, params);
    window.axios.post('/collect', {
        category: category,
        action: action,
        label: label
    });
}

$(document).ready(function () {

    $('a[data-ga-label],a[data-ga-action]').click(function (event) {
        var action = $(event.target).closest('[data-ga-action]').data('ga-action');
        var label = $(event.target).closest('[data-ga-label]').data('ga-label');
        var category = $(event.target).closest('[data-ga-category]').data('ga-category');
        trackEvent(category, action || 'click', label);
    });

    $('#add_assignment').submit(function (event) {
        if (!$('[name="name"]', this).val()) {
            return false;
        }
        var selected = $('[name="grammar"] option:selected', this);
        var label = selected.closest('[label]').attr('label') + ': ' + selected.text();
        trackEvent('Assignment', 'create', label);
    });

    //manipulation of the checkbox fields on text analyser page
    $(document).on('change', '.ref_chk input[type=checkbox]' , function () {
        var r = $(this).val();
        /*if($(this).is(':checked')) {
            $('.wrapper .'+r).removeClass('hidemarkup');
        } else {
            $('.wrapper .'+r).addClass('hidemarkup');
        }*/
        if($(this).is(':checked')) {
            if(r==='modal') {
                $('.wrapper .std' + r).addClass('modall');
            } else {
                $('.wrapper .std' + r).addClass(r);
            }
        } else {
            if(r==='modal') {
                $('.wrapper .std' + r).removeClass('modall');
            } else {
                $('.wrapper .std' + r).removeClass(r);
            }
        }
    });

    //manipulation of the checkbox fields on text analyser page
    $(document).on('change', '.ref_chk input[type=checkbox]' , function () {
        var r = $(this).val();
        if($(this).is(':checked')) {
            $('.wrapper .'+r).removeClass('hidemarkup');
        } else {
            $('.wrapper .'+r).addClass('hidemarkup');
        }
    });

    $('span[data-toggle=tooltip]').tooltip();

    //assignment feature info show hide
    let assignment_grammar = $("#grammar").val();

    $('div[class="feature_info"]').each(function(index,item){
        if(parseInt($(item).data('index')) === parseInt(assignment_grammar)){
            $(item).removeClass('d-none');
        } else {
            $(item).addClass('d-none');
        }
    });
    $("#grammar").change(function () {
        let now_sel = $(this).val();
        $('.feature_info').each(function(index,item){
            if(parseInt($(item).data('index')) === parseInt(now_sel)){
                $(item).removeClass('d-none');
            } else {
                $(item).addClass('d-none');
            }
        });
    });

    $('#refresh').click(function(){
        $.ajax({
            type: 'GET',
            url: '/help/refreshcaptcha',
            success:function(data){
                $('.captcha span').html(data.captcha);
            }
        });
    });

});
