const state = {
  loader: false
};

const getters = {
  loader: state => state.loader
};

const mutations = {
  setLoader (state, loading) {
    state.loader = loading;
  }
};

const actions = {
  setLoader (context, loading) {
    context.commit('setLoader', loading);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
