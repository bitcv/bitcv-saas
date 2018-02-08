import Vue from 'vue'
import Router from 'vue-router'

import Social from '@/components/social/Social'
import Media from '@/components/media/Media'
import Login from '@/components/login/Login'
import EditProject from '@/components/projList/editProject/EditProject'

Vue.use(Router)

export default new Router({
  // mode: 'history',
  routes: [
    {
      path: '/',
      component: Login
    },
    {
      path: '/login',
      component: Login
    },
    {
      path: '/info',
      component: EditProject
    },
    {
      path: '/social',
      component: Social
    },
    {
      path: '/media',
      component: Media
    }
  ]
})
