const moduleRoutes = {
  path: "/invoice/saleReturns",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'saleReturn.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'saleReturn.list',
        icon: 'add_shopping_cart',
        permissions: ['list-saleReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.saleReturn' },
        ]
      },
    },
    {
      path: 'create',
      name: 'saleReturn.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'saleReturn.create',
        permissions: ['create-saleReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.saleReturn', to: 'saleReturn.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'saleReturn.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'saleReturn.edit',
        permissions: ['edit-saleReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.saleReturn', to: 'saleReturn.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'saleReturn.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'saleReturn.import_csv',
        permissions: ['import-file-saleReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.saleReturn', to: 'saleReturn.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'saleReturn.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'saleReturn.pdf',
        // permissions: ['edit-saleReturn', 'show-saleReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.saleReturn', to: 'saleReturn.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
