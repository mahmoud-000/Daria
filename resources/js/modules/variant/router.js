const moduleRoutes = {
  path: "/variants",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'variant.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'variant.list',
        icon: 'pin',
        permissions: ['list-variant'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.variant' },
        ]
      },
    },
    {
      path: 'create',
      name: 'variant.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'variant.create',
        permissions: ['create-variant'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.variant', to: 'variant.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'variant.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'variant.edit',
        permissions: ['edit-variant'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.variant', to: 'variant.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'variant.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'variant.import_csv',
        permissions: ['import-file-variant'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.variant', to: 'variant.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'variant.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'variant.pdf',
        // permissions: ['edit-variant', 'show-variant'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.variant', to: 'variant.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
