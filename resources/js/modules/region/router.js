const moduleRoutes = {
  path: "/regions",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'region.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'region.list',
        icon: 'local_police',
        permissions: ['list-region'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.region' },
        ]
      },
    },
    {
      path: 'create',
      name: 'region.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'region.create',
        permissions: ['create-region'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.region', to: 'region.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'region.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'region.edit',
        permissions: ['edit-region'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.region', to: 'region.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'region.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'region.import_csv',
        permissions: ['import-file-region'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.region', to: 'region.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'region.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'region.pdf',
        // permissions: ['edit-region', 'show-region'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.region', to: 'region.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
