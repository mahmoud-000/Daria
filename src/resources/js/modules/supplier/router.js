const moduleRoutes = {
  path: "/people/suppliers",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'supplier.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'supplier.list',
        icon: 'fa-solid fa-people-carry-box',
        permissions: ['list-supplier'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.supplier' },
        ]
      },
    },
    {
      path: 'create',
      name: 'supplier.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'supplier.create',
        permissions: ['create-supplier'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.supplier', to: 'supplier.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'supplier.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'supplier.edit',
        permissions: ['edit-supplier'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.supplier', to: 'supplier.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'supplier.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'supplier.import_csv',
        permissions: ['import-file-supplier'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.supplier', to: 'supplier.list' },
          { label: 'action.import' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
