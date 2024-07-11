const moduleRoutes = {
  path: "/people/delegates",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'delegate.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'delegate.list',
        icon: 'fa-solid fa-truck-pickup',
        permissions: ['list-delegate'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.delegate' },
        ]
      },
    },
    {
      path: 'create',
      name: 'delegate.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'delegate.create',
        permissions: ['create-delegate'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.delegate', to: 'delegate.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'delegate.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'delegate.edit',
        permissions: ['edit-delegate'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.delegate', to: 'delegate.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'delegate.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'delegate.import_csv',
        permissions: ['import-file-delegate'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.delegate', to: 'delegate.list' },
          { label: 'action.import' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
