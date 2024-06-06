const moduleRoutes = {
    path: "/people",
    component: () => import('./Module.vue'),
    children: [
        {
            path: '',
            name: 'people.list',
            component: () => import('./views/index.vue'),
            meta: {
                layout: 'content',
                auth: true,
                title: 'people.list',
                icon: 'people',
                permissions: ['*'],
                // permissions: ['list-peoples'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.people' },
                ]
            }
        }
    ]
};

export default router => {
    router.addRoute(moduleRoutes)
}
