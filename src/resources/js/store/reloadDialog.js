const state = {
  reload_dialog: false,
};

const getters = {
  reload_dialog: state => state.reload_dialog,
};

const mutations = {
  setReloadDialog(state, reload_dialog) {
    state.reload_dialog = reload_dialog;
  },
};

const actions = {
  setReloadDialog(context, reload_dialog) {
    context.commit('setReloadDialog', reload_dialog);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
