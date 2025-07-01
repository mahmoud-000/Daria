const moduleRoutes = {
  path: "/invoice/purchases",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'purchase.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchase.list',
        icon: 'fa-solid fa-cart-plus',
        permissions: ['list-purchase'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchase' },
        ]
      },
    },
    {
      path: 'create',
      name: 'purchase.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchase.create',
        permissions: ['create-purchase'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchase', to: 'purchase.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'purchase.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchase.edit',
        permissions: ['edit-purchase'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchase', to: 'purchase.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'purchase.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchase.import_csv',
        permissions: ['import-file-purchase'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchase', to: 'purchase.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'purchase.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchase.pdf',
        // permissions: ['edit-purchase', 'show-purchase'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchase', to: 'purchase.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
