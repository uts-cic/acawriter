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
        }
    },
    mutations: {
        UPDATE_FEEDBACK: (state, {feedback }) => {
            state.feedback = feedback;
        }

    },
    getters: {
        currentFeedback: state => state.feedback

    },
    modules: {

    }
})

export default store