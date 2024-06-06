import { createRouter, createWebHistory } from 'vue-router'
import { getToken } from '../utils/auth';
import { getPageTitle } from '../utils/get_page_title';
import { canAccess } from '../utils/helpers'
const routes = [];

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeResolve((to, from, next) => {
  if (router.getRoutes().length) {
    document.title = getPageTitle(to.meta.title)

    if (!!getToken() && to.name === 'login') next({ name: 'dashboard' })

    else if (to.meta.auth && !getToken()) next({ name: 'login', query: { redirect: to.path } })

    else if (!!getToken() && !canAccess(to)) next({ name: '403' })

    else next()
  }

})

export default router
