// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import axios from 'axios'
import common from './common'
import store from './store'
Vue.use(common)

require('es6-promise').polyfill()
Vue.prototype.$http = axios
Vue.config.productionTip = false

Vue.use(ElementUI)

axios.interceptors.response.use(
  response => {
    if (response.data['errcode'] === 302) {
      location.href = '/signin'
    }
    return response
  },
  error => {
    if (error.response.status === 302) {
      location.href = '/signin'
    }
    return Promise.reject(error)
  }
)

// 同步缓存中的用户信息
const syncUserInfo = () => store.commit('updateUserInfo')

const getToken = () => {
  syncUserInfo()

  const authUserInfo = store.state.authUserInfo

  return authUserInfo && Object.keys(authUserInfo).length > 0
}

// 登录拦截
router.beforeEach((to, from, next) => {
  if (to.matched.some(route => route.meta && route.meta.requireAuth)) { // 需要校验登录信息
    const hasToken = getToken()

    if (!hasToken) { // 没有token
      // 跳转到登录页
      next('/admin/signin')
    }
  }

  next()
})

syncUserInfo()

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
