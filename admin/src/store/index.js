import Vue from 'vue'
import Vuex from 'vuex'
import * as actions from './actions'
import * as mutations from './mutations'

Vue.use(Vuex)

export default new Vuex.Store({
  actions,
  mutations,
  state: {
    // 保存用户登录状态
    authUserInfo: null
  }
})
