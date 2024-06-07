const moduleRoutes = {
  path: "/branches",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'branch.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'branch.list',
        icon: 'branch',
        permissions: ['list-branch'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.branch' },
        ]
      },
    },
    {
      path: 'create',
      name: 'branch.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'branch.create',
        permissions: ['create-branch'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.branch', to: 'branch.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'branch.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'branch.edit',
        permissions: ['edit-branch'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.branch', to: 'branch.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'branch.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'branch.import_csv',
        permissions: ['import-file-branch'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.branch', to: 'branch.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'branch.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'branch.pdf',
        // permissions: ['edit-branch', 'show-branch'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.branch', to: 'branch.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
