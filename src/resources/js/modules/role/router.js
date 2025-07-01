const moduleRoutes = {
  path: "/roles",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'role.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'role.list',
        icon: 'fa-solid fa-key',
        permissions: ['list-role'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.role' },
        ]
      },
    },
    {
      path: 'create',
      name: 'role.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'role.create',
        permissions: ['create-role'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.role', to: 'role.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'role.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'role.edit',
        permissions: ['edit-role', 'show-role'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.role', to: 'role.list' },
          { label: 'action.edit' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
