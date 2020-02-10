import Vue from "vue";
import Vuex from "vuex";
import loader from "../store/loader";
import snackbar from "../store/snackbar";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        loader,
        snackbar,
    }
});
