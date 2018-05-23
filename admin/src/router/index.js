import Vue from 'vue'
import Router from 'vue-router'
import Signin from '@/components/signin/Signin'
import Home from '@/components/home/Home'
import Setting from '@/components/setting/Setting'
import Upload from '@/components/setting/upload'
import ProjList from '@/components/projList/ProjList'
import ProjData from '@/components/projdata/projdata'
import MediaReport from '@/components/media/MediaReport'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [{
    path: '/admin',
    component: Home,
    children: [{
      path: '/admin/setting',
      meta: {
        requireAuth: true
      },
      component: Setting
    }, {
      path: '/admin/project',
      meta: {
        requireAuth: true
      },
      component: ProjList
    }, {
      path: '/admin/projdata',
      meta: {
        requireAuth: true
      },
      component: ProjData
    }, {
      path: '/admin/mediareport',
      meta: {
        requireAuth: true
      },
      component: MediaReport
    }, {
      path: '/admin/upload',
      meta: {
        requireAuth: true
      },
      component: Upload
    }]
  }, {
    path: '/admin/signin',
    component: Signin
  }]
})
