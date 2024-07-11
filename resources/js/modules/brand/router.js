const moduleRoutes = {
  path: "/brands",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'brand.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'brand.list',
        icon: 'fa-solid fa-ring',
        permissions: ['list-brand'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.brand' },
        ]
      },
    },
    {
      path: 'create',
      name: 'brand.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'brand.create',
        permissions: ['create-brand'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.brand', to: 'brand.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'brand.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'brand.edit',
        permissions: ['edit-brand'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.brand', to: 'brand.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'brand.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'brand.import_csv',
        permissions: ['import-file-brand'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.brand', to: 'brand.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'brand.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'brand.pdf',
        // permissions: ['edit-brand', 'show-brand'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.brand', to: 'brand.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}