const state = {
  user: false,
};

const getters = {
  user: state => state.user
};

const mutations = {
  setUser(user) {
    state.user = user;
  }
};

const actions = {
  setUser(context, user) {
    context.commit('setUser', user);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
