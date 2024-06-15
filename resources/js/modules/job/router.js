const moduleRoutes = {
  path: "/jobs",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'job.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'job.list',
        icon: 'local_police',
        permissions: ['list-job'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.job' },
        ]
      },
    },
    {
      path: 'create',
      name: 'job.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'job.create',
        permissions: ['create-job'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.job', to: 'job.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'job.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'job.edit',
        permissions: ['edit-job'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.job', to: 'job.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'job.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'job.import_csv',
        permissions: ['import-file-job'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.job', to: 'job.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'job.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'job.pdf',
        // permissions: ['edit-job', 'show-job'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.job', to: 'job.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
