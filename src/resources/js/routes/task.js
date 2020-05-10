import Index from '../pages/task/Index';

const routes = [
  {
    name: 'home',
    path: '/home',
    component: Index,
    meta: {
      title: 'Todog',
    }
  }
];

export default {
  routes,
};
