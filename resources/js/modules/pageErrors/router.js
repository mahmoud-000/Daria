const moduleRoutes = {
  path: "/",
  component: () => import('./Module.vue'),
  children: [
    {
      path: ":catchAll(.*)",
      name: '404',
      component: () => import('./views/404.vue'),
      meta: { layout: 'content', title: 'not_found' }
    },
    {
      path: "403",
      name: '403',
      component: () => import('./views/403.vue'),
      meta: { layout: 'content', title: 'not_access' }
    }
  ]
};

export default router => {
  router.addRoute(moduleRoutes)
}