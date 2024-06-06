const moduleRoutes = {
  path: "/units",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'unit.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'unit.list',
        icon: 'balance',
        permissions: ['list-unit'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.unit' },
        ]
      },
    },
    {
      path: 'create',
      name: 'unit.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'unit.create',
        permissions: ['create-unit'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.unit', to: 'unit.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'unit.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'unit.edit',
        permissions: ['edit-unit'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.unit', to: 'unit.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'unit.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'unit.import_csv',
        permissions: ['import-file-unit'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.unit', to: 'unit.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'unit.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'unit.pdf',
        // permissions: ['edit-unit', 'show-unit'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.unit', to: 'unit.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
