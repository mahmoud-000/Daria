const moduleRoutes = {
  path: "/organization/companies",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'company.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'company.list',
        icon: 'fa-regular fa-building',
        permissions: ['list-company'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.company' },
        ]
      },
    },
    {
      path: 'create',
      name: 'company.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'company.create',
        permissions: ['create-company'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.company', to: 'company.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'company.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'company.edit',
        permissions: ['edit-company'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.company', to: 'company.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'company.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'company.import_csv',
        permissions: ['import-file-company'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.company', to: 'company.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'company.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'company.pdf',
        // permissions: ['edit-company', 'show-company'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.company', to: 'company.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
