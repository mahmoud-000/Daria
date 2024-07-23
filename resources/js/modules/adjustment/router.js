const moduleRoutes = {
  path: "/invoice/adjustments",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'adjustment.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'adjustment.list',
        icon: 'fa-solid fa-plus-minus',
        permissions: ['list-adjustment'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.adjustment' },
        ]
      },
    },
    {
      path: 'create',
      name: 'adjustment.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'adjustment.create',
        permissions: ['create-adjustment'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.adjustment', to: 'adjustment.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'adjustment.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'adjustment.edit',
        permissions: ['edit-adjustment'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.adjustment', to: 'adjustment.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'adjustment.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'adjustment.import_csv',
        permissions: ['import-file-adjustment'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.adjustment', to: 'adjustment.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'adjustment.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'adjustment.pdf',
        // permissions: ['edit-adjustment', 'show-adjustment'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.adjustment', to: 'adjustment.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
