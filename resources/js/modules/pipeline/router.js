const moduleRoutes = {
  path: "/pipelines",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'pipeline.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'pipeline.list',
        icon: 'fa-solid fa-boxes-stacked',
        permissions: ['list-pipeline'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.pipeline' },
        ]
      },
    },
    {
      path: 'create',
      name: 'pipeline.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'pipeline.create',
        permissions: ['create-pipeline'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.pipeline', to: 'pipeline.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'pipeline.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'pipeline.edit',
        permissions: ['edit-pipeline'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.pipeline', to: 'pipeline.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'pipeline.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'pipeline.import_csv',
        permissions: ['import-file-pipeline'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.pipeline', to: 'pipeline.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'pipeline.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'pipeline.pdf',
        // permissions: ['edit-pipeline', 'show-pipeline'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.pipeline', to: 'pipeline.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
