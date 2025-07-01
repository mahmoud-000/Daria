const moduleRoutes = {
  path: "/stocks",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'stock.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stock',
      },
    },
    {
      path: 'create',
      name: 'stocks.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stock.create',
      },
    },
    {
      path: ':id/edit',
      name: 'stock.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stock.edit',
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}