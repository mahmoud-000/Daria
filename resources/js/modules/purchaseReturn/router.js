const moduleRoutes = {
  path: "/invoice/purchaseReturns",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'purchaseReturn.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchaseReturn.list',
        icon: 'fa-solid fa-file-arrow-up',
        permissions: ['list-purchaseReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchaseReturn' },
        ]
      },
    },
    {
      path: 'create',
      name: 'purchaseReturn.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchaseReturn.create',
        permissions: ['create-purchaseReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchaseReturn', to: 'purchaseReturn.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'purchaseReturn.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchaseReturn.edit',
        permissions: ['edit-purchaseReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchaseReturn', to: 'purchaseReturn.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'purchaseReturn.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchaseReturn.import_csv',
        permissions: ['import-file-purchaseReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchaseReturn', to: 'purchaseReturn.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'purchaseReturn.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'purchaseReturn.pdf',
        // permissions: ['edit-purchaseReturn', 'show-purchaseReturn'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.purchaseReturn', to: 'purchaseReturn.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
