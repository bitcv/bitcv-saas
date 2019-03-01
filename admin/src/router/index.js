import Vue from 'vue'
import Router from 'vue-router'
import Signin from '@/components/signin/Signin'
import Home from '@/components/home/Home'
import Setting from '@/components/setting/Setting'
import Upload from '@/components/setting/upload'
import ProjList from '@/components/projList/ProjList'
import ProjData from '@/components/projdata/projdata'
import MediaReport from '@/components/media/MediaReport'
import Stat from '@/components/stat/redpacket'
import YbbStat from '@/components/stat/ybbstat'
import ZPStat from '@/components/stat/zpstat'
import AssetStat from '@/components/stat/assetstat'
import OtcStat from '@/components/stat/otcstat'
import GenPicture from '@/components/stat/genpicture'
import BBExchange from '@/components/stat/exchange'
import BBExchangeDetail from '@/components/stat/exchangeDetail'
import BBRechargeRecord from '@/components/stat/rechargeRecord'
import BBMyExchange from '@/components/stat/myExchange'

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
    }, {
      path: '/admin/ybbstat',
      meta: {
        requireAuth: true
      },
      component: YbbStat
    }, {
      path: '/admin/zpstat',
      meta: {
        requireAuth: true
      },
      component: ZPStat
    }, {
      path: '/admin/assetstat',
      meta: {
        requireAuth: true
      },
      component: AssetStat
    }, {
      path: '/admin/genpicture',
      meta: {
        requireAuth: true
      },
      component: GenPicture
    }, {
      path: '/admin/exchange',
      meta: {
        requireAuth: true
      },
      component: BBExchange
    }, {
      path: '/admin/exchangeDetail/:id',
      meta: {
        requireAuth: true
      },
      component: BBExchangeDetail
    }, {
      path: '/admin/rechargeRecord/:id',
      meta: {
        requireAuth: true
      },
      component: BBRechargeRecord
    }, {
      path: '/admin/myExchange/:id',
      meta: {
        requireAuth: true
      },
      component: BBMyExchange
    }, {
      path: '/admin/otcstat',
      meta: {
        requireAuth: true
      },
      component: OtcStat
    }, {
      path: '/admin/stat',
      meta: {
        requireAuth: true
      },
      component: Stat
    }]
  }, {
    path: '/admin/signin',
    component: Signin
  }]
})
