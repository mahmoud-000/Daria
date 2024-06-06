const moduleRoutes = {
  path: "/",
  component: () => import('./Module.vue'),
  children: [
    {
      path: 'login',
      name: 'login',
      component: () => import('./views/Login.vue'),
      meta: {
        layout: 'blank',
        auth: false,
        title: 'login'
      }
    },
    {
      path: 'forget_password',
      name: 'forget_password',
      component: () => import('./views/ForgetPassword.vue'),
      meta: {
        auth: false,
        layout: 'blank',
        title: 'forget_password',
      }
    },
    {
      path: 'reset_password',
      name: 'reset_password',
      component: () => import('./views/ResetPassword.vue'),
      meta: {
        auth: false,
        layout: 'blank',
        title: 'reset_password',
      }
    },
    {
      path: 'profile',
      name: 'user-profile',
      component: () => import('./views/UserProfile.vue'),
      meta: {
        auth: true,
        layout: 'content',
        title: 'profile',
        permissions: ['show-user-profile'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.profile'},
          { label: 'action.edit' },
        ]
      }
    },
    {
      path: 'customer-profile',
      name: 'customer-profile',
      component: () => import('./views/CustomerProfile.vue'),
      meta: {
        auth: true,
        layout: 'content',
        title: 'profile',
        permissions: ['show-customer-profile'],
        breadcrumbs: [
          { label: 'links.home', to: 'dashboard' },
          { label: 'links.profile'},
          { label: 'action.edit' },
        ]
      }
    },
    {
      path: 'register',
      name: 'register',
      component: () => import('./views/RegisterCustomer.vue'),
      meta: {
        layout: 'blank',
        auth: false,
        title: 'register_customer'
      }
    },
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}