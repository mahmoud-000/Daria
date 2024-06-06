const moduleRoutes = {
    path: "/invoice",
    component: () => import('./Module.vue'),
    children: [
        {
            path: '',
            name: 'invoice.list',
            component: () => import('./views/index.vue'),
            meta: {
                layout: 'content',
                auth: true,
                title: 'invoice.list',
                icon: 'description',
                permissions: ['*'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.invoice' },
                ]
            }
        }
    ]
};

export default router => {
    router.addRoute(moduleRoutes)
}
