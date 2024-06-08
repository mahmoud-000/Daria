const moduleRoutes = {
  path: "/stages",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'stage.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stage.list',
        icon: 'filter_alt',
        permissions: ['list-stage'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.stage' },
        ]
      },
    },
    {
      path: 'create',
      name: 'stage.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stage.create',
        permissions: ['create-stage'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.stage', to: 'stage.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'stage.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stage.edit',
        permissions: ['edit-stage'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.stage', to: 'stage.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'stage.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stage.import_csv',
        permissions: ['import-file-stage'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.stage', to: 'stage.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'stage.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'stage.pdf',
        // permissions: ['edit-stage', 'show-stage'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.stage', to: 'stage.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
