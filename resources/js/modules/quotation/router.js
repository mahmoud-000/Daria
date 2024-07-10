const moduleRoutes = {
  path: "/invoice/quotations",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'quotation.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'quotation.list',
        icon: 'add_shopping_cart',
        permissions: ['list-quotation'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.quotation' },
        ]
      },
    },
    {
      path: 'create',
      name: 'quotation.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'quotation.create',
        permissions: ['create-quotation'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.quotation', to: 'quotation.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'quotation.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'quotation.edit',
        permissions: ['edit-quotation'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.quotation', to: 'quotation.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'quotation.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'quotation.import_csv',
        permissions: ['import-file-quotation'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.quotation', to: 'quotation.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'quotation.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'quotation.pdf',
        // permissions: ['edit-quotation', 'show-quotation'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.quotation', to: 'quotation.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
