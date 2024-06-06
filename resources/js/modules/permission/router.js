const moduleRoutes = {
  path: "/permissions",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'permission.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'permission',
      },
    },
    {
      path: 'create',
      name: 'permissions.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'permission.create',
      },
    },
    {
      path: ':id/edit',
      name: 'permission.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'permission.edit',
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}