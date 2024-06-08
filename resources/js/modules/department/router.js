const moduleRoutes = {
  path: "/organization/departments",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'department.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'department.list',
        icon: 'department',
        permissions: ['list-department'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.department' },
        ]
      },
    },
    {
      path: 'create',
      name: 'department.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'department.create',
        permissions: ['create-department'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.department', to: 'department.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'department.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'department.edit',
        permissions: ['edit-department'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.department', to: 'department.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'department.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'department.import_csv',
        permissions: ['import-file-department'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.department', to: 'department.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'department.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'department.pdf',
        // permissions: ['edit-department', 'show-department'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.department', to: 'department.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
