const moduleRoutes = {
  path: "/warehouses",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'warehouse.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'warehouse.list',
        icon: 'warehouse',
        permissions: ['list-warehouse'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.warehouse' },
        ]
      },
    },
    {
      path: 'create',
      name: 'warehouse.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'warehouse.create',
        permissions: ['create-warehouse'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.warehouse', to: 'warehouse.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'warehouse.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'warehouse.edit',
        permissions: ['edit-warehouse'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.warehouse', to: 'warehouse.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'warehouse.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'warehouse.import_csv',
        permissions: ['import-file-warehouse'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.warehouse', to: 'warehouse.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'warehouse.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'warehouse.pdf',
        // permissions: ['edit-warehouse', 'show-warehouse'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.warehouse', to: 'warehouse.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
