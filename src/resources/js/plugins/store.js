import Vue from "vue";
import Vuex from "vuex";
import loader from "../store/loader";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        loader,
    }
});
