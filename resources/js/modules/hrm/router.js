const moduleRoutes = {
    path: "/hrm",
    component: () => import('./Module.vue'),
    children: [
        {
            path: '',
            name: 'hrm.list',
            component: () => import('./views/index.vue'),
            meta: {
                layout: 'content',
                auth: true,
                title: 'hrm.list',
                icon: 'hr',
                permissions: ['*'],
                // permissions: ['list-hrms'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.hrm' },
                ]
            }
        }
    ]
};

export default router => {
    router.addRoute(moduleRoutes)
}
