import Vue from 'vue';
//import VueTimepicker from 'vue2-timepicker';
import axios from 'axios';
import VueRouter from 'vue-router';

import Home from './components/Home.vue'
Vue.use(axios);
Vue.use(VueRouter);

const routes = [

    {
        path: '/',
        component: Home,
        name: 'defaultProductList'
    },

];
const router =new VueRouter({
    routes: routes
});


const app=new Vue({
    el:'#app',
    router
});

