import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)


const store = new Vuex.Store({
    state: {
        feedback :[],
        loading: ''
    },
    actions: {
        LOAD_FEEDBACK: function ({ commit, state }, params) {
            commit( 'SET_LOADING', {status:'Processing - step 1 of 2 completed'} );
            axios.post('/feedback', params).then((response) => {
                commit( 'SET_LOADING', {status:'Processing - step  2 of 2 completed'} );
                commit('UPDATE_FEEDBACK', { feedback: response.data })
            }, (err) => {
                console.log(err)
            })
        },
        FETCH_TOKENISED_FEEDBACK: function({commit, state}, params){
            axios.post('/feedback', params.send).then((response) => {
                if(params.act==='update') {
                    commit('UPDATE_SINGLE_FEEDBACK', {feedback: response.data, idx: params.idx})
                } else {
                    commit('ADD_SINGLE_FEEDBACK', {feedback: response.data, idx: params.idx})
                }
            }, (err) => {
                console.log(err)
            })
        },
        PRELOAD_FEEDBACK: function ({ commit, state }, params) {
            commit('UPDATE_FEEDBACK', { feedback: params.savedFeed });
        },
    },
    mutations: {
        UPDATE_FEEDBACK: (state, {feedback }) => {
            state.feedback = feedback;
            state.loading = '';
        },
        UPDATE_SINGLE_FEEDBACK: (state, { feedback, idx }) => {
            if(idx!==-1) state.feedback.final[idx] = feedback.final[0];
        },
        ADD_SINGLE_FEEDBACK: (state, { feedback, idx }) => {
            if(idx!==-1) {
                state.feedback.final[idx] = feedback.final[0];
            }
        },
        SET_LOADING: (state, {status}) => {
            state.loading = status;
        }


    },
    getters: {
        currentFeedback: state => state.feedback,
        loadingStatus: state => state.loading
    },
    modules: {

    }
})

export default store
