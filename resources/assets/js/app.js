
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

//Vue.component('example', require('./components/Example.vue'));
Vue.component('log-status', require('./components/lstatus.vue'));
Vue.component('doc-editor', require('./components/TextAnalyser.vue'));
Vue.component('internet-connection', require('./components/InternetConnection.vue'));
Vue.component('tap-status', require('./components/TapHealth.vue'));
Vue.component('autocomplete', require('./components/Autocomplete.vue'));



var socket = io.connect('http://awa3.utscic.edu.au:3000');
import moment from 'moment';

const app = new Vue({
    el: '#app',
    apolloProvider,
    data: {
        slogs: [],
        lastHeartBeatReceivedAt: moment(),
        tapHealth: 'failed'
    },
    mounted: function() {

        socket.on('operational-log:App\\Events\\OperationLog', function(data){
            return this.slogs.push(data.details);
        }.bind(this));

        socket.on('private-dashboard:App\\Events\\InternetConnection\\Heartbeat', function () {
            console.log("ok yes listened");
            return this.lastHeartBeatReceivedAt = moment().format('LLL');
        }.bind(this));

        socket.on('private-dashboard:App\\Events\\Tap\\Health', function (data) {
            console.log("ok yes listened tap health");
            return this.tapHealth=data.health.message;
        }.bind(this));

    },
    methods: {
        processLog (txt) {
            console.log(txt);
        }
    }
});
