import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)


const store = new Vuex.Store({
    state: {
        feedback :[]
    },
    actions: {
        LOAD_FEEDBACK: function ({ commit, state }, params) {
            axios.post('/feedback', params).then((response) => {
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
        }
    },
    mutations: {
        UPDATE_FEEDBACK: (state, {feedback }) => {
            state.feedback = feedback;
        },
        UPDATE_SINGLE_FEEDBACK: (state, { feedback, idx }) => {
            if(idx!==-1) state.feedback.final[idx] = feedback.final[0];
        },
        ADD_SINGLE_FEEDBACK: (state, { feedback, idx }) => {
            if(idx!==-1) {
                state.feedback.final[idx] = feedback.final[0];
            }
        }

    },
    getters: {
        currentFeedback: state => state.feedback
    },
    modules: {

    }
})

export default store