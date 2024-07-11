const moduleRoutes = {
  path: "/people/customers",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'customer.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'customer.list',
        icon: 'fa-solid fa-people-robbery',
        permissions: ['list-customer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.customer' },
        ]
      },
    },
    {
      path: 'create',
      name: 'customer.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'customer.create',
        permissions: ['create-customer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.customer', to: 'customer.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'customer.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'customer.edit',
        permissions: ['edit-customer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.customer', to: 'customer.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'customer.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'customer.import_csv',
        permissions: ['import-file-customer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.customer', to: 'customer.list' },
          { label: 'action.import' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
