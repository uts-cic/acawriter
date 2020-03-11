import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

const QUIET = ['/collect', '/feedback/store'];

axios.interceptors.request.use(config => {
    let quiet = QUIET.includes(config.url);
    !quiet && NProgress.start();
    return config;
});

axios.interceptors.response.use(response => {
    let quiet = QUIET.includes(response.config.url);
    !quiet && NProgress.done();
    return response;
});

const store = new Vuex.Store({
    state: {
        feedback: [],
        loading: ''
    },
    actions: {
        LOAD_FEEDBACK: function ({ commit, state }, params) {
            commit('SET_LOADING', {status:'Processing - step 1 of 2 completed'});
            axios.post('/feedback', params).then((response) => {
                if (!response.data.rules) {
                    return alert('An error occured during your request. Please try again. If this happens repeatedly, reduce the amount of text being processed and try again.');
                }
                commit('SET_LOADING', { status:'Processing - step  2 of 2 completed' });
                commit('UPDATE_FEEDBACK', { feedback: response.data });
            }, (err) => {
                console.log(err);
            })
        },
        FETCH_TOKENISED_FEEDBACK: function({ commit, state }, params) {
            commit('SET_LOADING', { status: 'Auto feedback initiated' });
            axios.post('/feedback', params.send).then((response) => {
                if (params.act === 'update') {
                    commit('UPDATE_SINGLE_FEEDBACK', { feedback: response.data, idx: params.idx });
                }
                else {
                    commit('ADD_SINGLE_FEEDBACK', { feedback: response.data, idx: params.idx });
                }
            }, (err) => {
                console.log(err);
            })
        },
        PRELOAD_FEEDBACK: function ({ commit, state }, params) {
            commit('UPDATE_FEEDBACK', { feedback: params.savedFeed });
        },
    },
    mutations: {
        UPDATE_FEEDBACK: (state, { feedback }) => {
            state.feedback = feedback;
            state.loading = '';
        },
        UPDATE_SINGLE_FEEDBACK: (state, { feedback, idx }) => {
            if (idx !== -1) {
                Vue.set(state.feedback.final, idx, feedback.final[0]);
            }
            state.loading = '';
        },
        ADD_SINGLE_FEEDBACK: (state, { feedback, idx }) => {
            if (idx !== -1) {
                Vue.set(state.feedback.final, idx, feedback.final[0]);
            }
            state.loading = '';
        },
        SET_LOADING: (state, { status }) => {
            state.loading = status;
        }
    },
    getters: {
        currentFeedback: state => state.feedback,
        loadingStatus: state => state.loading
    },
    modules: {}
});

export default store;
