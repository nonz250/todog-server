import Vue from 'vue';
import Vuex from 'vuex';
import loader from '../store/loader';
import snackbar from '../store/snackbar';
import auth from '../store/auth';
import reloadDialog from '../store/reloadDialog';
import swDialog from '../store/swDialog';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth,
    loader,
    snackbar,
    reloadDialog,
    swDialog
  }
});
