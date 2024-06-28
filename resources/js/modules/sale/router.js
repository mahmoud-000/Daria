const moduleRoutes = {
  path: "/invoice/sales",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'sale.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'sale.list',
        icon: 'add_shopping_cart',
        permissions: ['list-sale'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.sale' },
        ]
      },
    },
    {
      path: 'create',
      name: 'sale.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'sale.create',
        permissions: ['create-sale'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.sale', to: 'sale.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'sale.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'sale.edit',
        permissions: ['edit-sale'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.sale', to: 'sale.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'sale.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'sale.import_csv',
        permissions: ['import-file-sale'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.sale', to: 'sale.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'sale.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'sale.pdf',
        // permissions: ['edit-sale', 'show-sale'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.sale', to: 'sale.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
