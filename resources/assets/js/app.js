
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');
//import Vue from 'vue';




import { ApolloClient, createNetworkInterface } from 'apollo-client';
import VueApollo from 'vue-apollo';

//import modals lib
import VModal from 'vue-js-modal';

Vue.use(VModal);


// Create the apollo client
const apolloClient = new ApolloClient({
    networkInterface: createNetworkInterface({
        //uri: 'http://localhost:3020/graphql',
        uri: 'https://graphql-compose.herokuapp.com/northwind/',
    }),
    connectToDevTools: true,
})

// Install the vue plugin
Vue.use(VueApollo)

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('log-status', require('./components/lstatus.vue'));
Vue.component('doc-editor', require('./components/TextAnalyser.vue'));
Vue.component('internet-connection', require('./components/InternetConnection.vue'));
Vue.component('tap-status', require('./components/TapHealth.vue'));
Vue.component('autocomplete', require('./components/Autocomplete.vue'));
Vue.component('assignment-list', require('./components/Assignment.vue'));
Vue.component('documents', require('./components/Document.vue'));
Vue.component('edit-document', require('./components/modal/EditDocument.vue'));
Vue.component('example-text', require('./components/Example.vue'));
Vue.component('ex-doc-editor', require('./components/ExampleAnalyser.vue'));



var socket = io.connect(process.env.MIX_APP_SOCKET);
import moment from 'moment';
const app = new Vue({
    el: '#app',
    apolloProvider,
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
            //console.log("ok yes listened");
            return this.lastHeartBeatReceivedAt = moment().format('LLL');
        }.bind(this));

        socket.on('private-dashboard:App\\Events\\Tap\\Health', function (data) {
            //console.log("ok yes listened tap health");
            return this.tapHealth=data.health.message;
        }.bind(this));

        socket.on('private-user-activity:App\\Events\\UserActivity', function (data) {
            console.log("into user activity");
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

/*(function ($) {

    'use strict';

    // Toggle classes in body for syncing sliding animation with other elements
    $('#bs-example-navbar-collapse-2')
        .on('show.bs.collapse', function (e) {
            $('#app').addClass('menu-slider');
        })
        .on('shown.bs.collapse', function (e) {
            $('#app').addClass('in');
        })
        .on('hide.bs.collapse', function (e) {
            $('#app').removeClass('menu-slider');
        })
        .on('hidden.bs.collapse', function (e) {
            $('#app').removeClass('in');
        });


})(jQuery);
*/

$(document).ready(function () {
    $('#sidebarCollapse, #sidebarCollapseTwice').on('click', function () {
        $('#sidebar').toggleClass('active');
    });


    //manipulation of the checkbox fields on text analyser page
    $(document).on('change', '#legend.ref input[type=checkbox]' , function () {
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
    $(document).on('change', '#legend.ana input[type=checkbox]' , function () {
        var r = $(this).val();
        if($(this).is(':checked')) {
            $('.wrapper .'+r).removeClass('hidemarkup');
        } else {
            $('.wrapper .'+r).addClass('hidemarkup');
        }
    });

    $('#extendOut').on('click', openWindow);


    function openWindow(){
        $('#sidebar').toggleClass('active');
        var w = window.open('','AWA3 key','width=500, height=600, toolbar=no,location=no,status=no,menubar=no,addressbar=0');
        var html = "<html><head><title>AWA3 Key</title><link rel=\"stylesheet\" type=\"text/css\" href='/css/app.css'></head><body>";
        html +=  $('#popup').html();
        html += '</body></html>';
        w.document.write(html);
    }


});