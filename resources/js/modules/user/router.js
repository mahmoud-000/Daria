const moduleRoutes = {
  path: "/people/users",
  component: () => import('./Module.vue'),
  children: [
    {
      path: '',
      name: 'user.list',
      component: () => import('./views/List.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'user.list',
        icon: 'people',
        permissions: ['list-user'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.user' },
        ]
      },
    },
    {
      path: 'create',
      name: 'user.create',
      component: () => import('./views/Create.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'user.create',
        permissions: ['create-user'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.user', to: 'user.list' },
          { label: 'action.create' },
        ]
      },
    },
    {
      path: ':id/edit',
      name: 'user.edit',
      component: () => import('./views/Edit.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'user.edit',
        permissions: ['edit-user'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.user', to: 'user.list' },
          { label: 'action.edit' },
        ]
      },
    },
    {
      path: 'import',
      name: 'user.import',
      component: () => import('./views/Import.vue'),
      meta: {
        layout: 'content',
        auth: true,
        title: 'user.import_csv',
        permissions: ['import-file-user'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.people', to: 'people.list' },
          { label: 'links.user', to: 'user.list' },
          { label: 'action.import' },
        ]
      },
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}
