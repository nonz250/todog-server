import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

import test from "../routes/test";
const routes = test.routes;

export default new VueRouter({
    mode: 'history',
    routes
});
