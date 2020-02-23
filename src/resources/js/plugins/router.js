import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

let routes = [];

import test from "../routes/test";
routes = routes.concat(test.routes);

import top from "../routes/top";
routes = routes.concat(top.routes);

import task from "../routes/task";
routes = routes.concat(task.routes);

import login from "../routes/login";
routes = routes.concat(login.routes);

export default new VueRouter({
    mode: 'history',
    routes
});
