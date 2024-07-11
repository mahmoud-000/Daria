const moduleRoutes = {
  path: "/items",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'item.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'item.list',
        icon: 'fa-solid fa-basket-shopping',
        permissions: ['list-item'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.item' },
        ]
      },
    },
    {
      path: 'create',
      name: 'item.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'item.create',
        permissions: ['create-item'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.item', to: 'item.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'item.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'item.edit',
        permissions: ['edit-item'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.item', to: 'item.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'item.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'item.import_csv',
        permissions: ['import-file-item'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.item', to: 'item.list' },
          { label: 'action.import' },
        ]
      },
    },
    {
      path: ':id/pdf',
      name: 'item.pdf',
      component: () => import('./views/Pdf.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'item.pdf',
        // permissions: ['edit-item', 'show-item'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.item', to: 'item.list' },
          { label: 'action.pdf' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
