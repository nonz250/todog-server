const state = {
  sw_dialog: false
};

const getters = {
  sw_dialog: state => state.sw_dialog
};

const mutations = {
  setSwDialog (state, sw_dialog) {
    state.sw_dialog = sw_dialog;
  }
};

const actions = {
  setSwDialog (context, sw_dialog) {
    context.commit('setSwDialog', sw_dialog);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
