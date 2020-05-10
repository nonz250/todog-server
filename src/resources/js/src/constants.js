export default {
  isDebug () {
    return process.env.NODE_ENV !== 'production';
  }
};
