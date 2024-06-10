const moduleRoutes = {
  path: "/attributes",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'attribute.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'attribute.list',
        icon: 'attribute',
        permissions: ['list-attribute'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.attribute' },
        ]
      },
    },
    {
      path: 'create',
      name: 'attribute.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'attribute.create',
        permissions: ['create-attribute'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.attribute', to: 'attribute.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'attribute.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'attribute.edit',
        permissions: ['edit-attribute'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.attribute', to: 'attribute.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'attribute.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'attribute.import_csv',
        permissions: ['import-file-attribute'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.attribute', to: 'attribute.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'attribute.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'attribute.pdf',
        // permissions: ['edit-attribute', 'show-attribute'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.attribute', to: 'attribute.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
