import Index from "../pages/task/Index";

const routes = [
    {
        name: 'task',
        path: '/task',
        component: Index,
        meta: {
            title: 'Todog',
        }
    }
];

export default {
    routes,
}
