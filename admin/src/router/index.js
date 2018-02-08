import Vue from 'vue'
import Router from 'vue-router'

import Social from '@/components/social/Social'
import Media from '@/components/media/Media'
import Login from '@/components/login/Login'
import EditProject from '@/components/projList/editProject/EditProject'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/admin',
      component: Login
    },
    {
      path: '/admin/info',
      component: EditProject
    },
    {
      path: '/admin/social',
      component: Social
    },
    {
      path: '/admin/media',
      component: Media
    }
  ]
})
