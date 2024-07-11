const moduleRoutes = {
    path: "/settings",
    component: () => import('./Module.vue'),
    children: [
        {
            path: '',
            name: 'setting.list',
            component: () => import('./views/index.vue'),
            meta: {
                layout: 'content',
                auth: true,
                title: 'setting.list',
                icon: 'fa-solid fa-sliders',
                permissions: ['*'],
                // permissions: ['list-settings'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.setting' },
                ]
            }
        },
        {
            path: 'system',
            name: 'setting.system',
            component: () => import('./views/system.vue'),
            meta: {
                layout: 'content',
                auth: true,
                title: 'setting.system',
                icon: 'fa-solid fa-gears',
                permissions: ['edit-system-settings'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.system' },
                    { label: 'action.edit' },
                ]
            }
        },
        {
            path: 'appearance',
            name: 'setting.appearance',
            component: () => import('./views/appearance.vue'),
            meta: {
                layout: 'content',
                auth: true,
                icon: 'fa-solid fa-palette',
                title: 'setting.appearance',
                permissions: ['*'],
                breadcrumbs: [
                    { label: 'links.home', to: 'dashboard' },
                    { label: 'links.appearance' },
                    { label: 'action.edit' },
                ]
            }
        }
    ]
};

export default router => {
    router.addRoute(moduleRoutes)
}
