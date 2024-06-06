const moduleRoutes = {
    path: "/organization",
    component: () => import('./Module.vue'),
    children: [
        {
            path: '',
            name: 'organization.list',
            component: () => import('./views/index.vue'),
            meta: {
                layout: 'content',
                auth: true,
                title: 'organization.list',
                icon: 'domain',
                permissions: ['*'],
                // permissions: ['list-organizations'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.organization' },
                ]
            }
        }
    ]
};

export default router => {
    router.addRoute(moduleRoutes)
}
