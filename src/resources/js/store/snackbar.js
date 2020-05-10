const state = {
  snackbar: false,
  text: '',
  color: '',
  position: 'top',
};

const getters = {
  snackbar: state => state.snackbar,
  text: state => state.text,
  color: state => state.color,
  position: state => state.position
};

const mutations = {
  setSnackbar(state, snackbar) {
    state.snackbar = snackbar;
  },
  setText(state, text) {
    state.text = text;
  },
  setColor(state, color) {
    state.color = color;
  },
  setPosition(state, position) {
    state.position = position;
  },
};

const actions = {
  setSnackbar(context, snackbar) {
    context.commit('setSnackbar', snackbar);
  },
  setText(context, text) {
    context.commit('setText', text);
  },
  setColor(context, color) {
    context.commit('setColor', color);
  },
  setPosition(context, position) {
    context.commit('setPosition', position);
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
};
