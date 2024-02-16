import { createStore } from "vuex";
import axiosClient from '../axios'

const savedUserData = JSON.parse(sessionStorage.getItem('USER_DATA'));

const store = createStore({
    state: {
        user: {
            data: savedUserData || {},
            token: sessionStorage.getItem("TOKEN"),
        },
        reminders: {
            loading: false,
            data: []
        },
        loginSuccess: false,
    },
    getters: {},
    actions: {
        register({ commit }, user) {
            return axiosClient.post('/register', user)
                .then(({ data }) => {
                    commit('setUser', data);
                    return data;
                })
        },
        login({ commit }, user) {
            return axiosClient.post('/login', user)
                .then(({ data }) => {
                    commit('setUser', data);
                    commit('setLoginSuccess', true);
                    return data;
                })
        },
        logout({ commit }) {
            return axiosClient.post('/logout')
                .then(response => {
                    commit('logout')
                    return response;
                })
        },
    },
    mutations: {
        logout: (state) => {
            state.user.token = null;
            state.user.data = {};
            sessionStorage.removeItem('TOKEN');
            sessionStorage.removeItem('USER_DATA'); 
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem('TOKEN', userData.token);
            sessionStorage.setItem('USER_DATA', JSON.stringify(userData.user)); 
        },
        setLoginSuccess: (state, success) => {
            state.loginSuccess = success;
        },
    },
});

export default store;
