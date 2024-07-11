const moduleRoutes = {
  path: "/categories",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'category.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'category.list',
        icon: 'fa-solid fa-layer-group',
        permissions: ['list-category'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.category' },
        ]
      },
    },
    {
      path: 'create',
      name: 'category.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'category.create',
        permissions: ['create-category'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.category', to: 'category.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'category.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'category.edit',
        permissions: ['edit-category'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.category', to: 'category.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'category.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'category.import_csv',
        permissions: ['import-file-category'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.category', to: 'category.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'category.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'category.pdf',
        // permissions: ['edit-category', 'show-category'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.category', to: 'category.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
