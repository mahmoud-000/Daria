const moduleRoutes = {
  path: "/invoice/transfers",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'transfer.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'transfer.list',
        icon: 'fa-solid fa-cart-plus',
        permissions: ['list-transfer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.transfer' },
        ]
      },
    },
    {
      path: 'create',
      name: 'transfer.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'transfer.create',
        permissions: ['create-transfer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.transfer', to: 'transfer.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'transfer.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'transfer.edit',
        permissions: ['edit-transfer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.transfer', to: 'transfer.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'transfer.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'transfer.import_csv',
        permissions: ['import-file-transfer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.transfer', to: 'transfer.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'transfer.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'transfer.pdf',
        // permissions: ['edit-transfer', 'show-transfer'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.transfer', to: 'transfer.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
