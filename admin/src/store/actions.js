// import Vue from 'vue'
import axios from 'axios'
import { MessageBox } from 'element-ui'

const handleErr = (msg) => {
  MessageBox.alert(msg, '提示', {
    confirmButtonText: '确定',
    center: true
  })
}

export const reLogin = ({commit}) => {
  commit('cleanUserInfo')
}

const resolveResponse = ({errcode, data, errmsg = ''}) => {
  switch (errcode) {
    case 0: return data
    case 302:
      // reLogin()
      handleErr(errmsg)
      return Promise.reject(errmsg)
    default:
      handleErr(errmsg)
      return Promise.reject(errmsg)
  }
}

const post = (url, params) => {
  return axios.post(url, params).then(res => resolveResponse(res.data))
}

const get = (url, params) => {
  return axios.get(url, params).then(res => resolveResponse(res.data))
}

/* ===========基本信息=========== */

// 获取基本信息
export const getProjBasicInfo = (store, params) => {
  return post('/api/getProjBasicInfo', params)
}
// 获取基本信息标签信息
export const getProjTagList = (store, params) => {
  return post('/api/getProjTagList', params)
}
// 修改基本信息
export const updProjBasicInfo = (store, params) => {
  return post('/api/updProjBasicInfo', params)
}

/* ===========团队成员=========== */

// 获取团队成员列表
export const getProjMemberList = (store, params) => {
  return post('/api/getProjMemberList', params)
}

// 新增团队成员
export const addProjIMember = (store, params) => {
  return post('/api/addProjIMember', params)
}

// 更新团队成员
export const updProjMember = (store, params) => {
  return post('/api/updProjMember', params)
}

// 删除团队成员
export const delProjMember = (store, params) => {
  return post('/api/delProjMember', params)
}

/* ===========发展历程=========== */

// 获取发展历程列表
export const getProjEventList = (store, params) => {
  return post('/api/getProjEventList', params)
}
// 新增发展历程
export const addProjEvent = (store, params) => {
  return post('/api/addProjEvent', params)
}
// 更新发展历程
export const updProjEvent = (store, params) => {
  return post('/api/updProjEvent', params)
}
// 删除发展历程
export const delProjEvent = (store, params) => {
  return post('/api/delProjEvent', params)
}

/* ===========社交信息=========== */

// 获取社交信息列表
export const getProjSocialList = (store, params) => {
  return post('/api/getProjSocialList', params)
}
// 获取社交信息下拉选项列表
export const getSocialList = (store, params) => {
  return get('/api/getSocialList', params)
}
// 新增社交信息
export const addProjSocial = (store, params) => {
  return post('/api/addProjSocial', params)
}
// 更新社交信息
export const updProjSocial = (store, params) => {
  return post('/api/updProjSocial', params)
}
// 删除社交信息
export const delProjSocial = (store, params) => {
  return post('/api/delProjSocial', params)
}

/* ===========项目顾问=========== */

// 获取项目顾问列表
export const getProjAdvisorList = (store, params) => {
  return post('/api/getProjAdvisorList', params)
}
// 获取项目顾问下拉选项列表
export const getAdvList = (store, params) => {
  return get('/api/getAdvList', params)
}
// 新增项目顾问（手动输入）
export const addProjAdvisor = (store, params) => {
  return post('/api/addProjAdvisor', params)
}
// 新增项目顾问 (直接选择)
export const addProjIAdvisor = (store, params) => {
  return post('/api/addProjIAdvisor', params)
}
// 更新项目顾问
export const updProjAdvisor = (store, params) => {
  return post('/api/updProjAdvisor', params)
}
// 删除项目顾问
export const delProjAdvisor = (store, params) => {
  return post('/api/delProjAdvisor', params)
}

/* ===========合作机构=========== */

// 获取合作机构列表
export const getProjPartnerList = (store, params) => {
  return post('/api/getProjPartnerList', params)
}
// 获取合作机构下拉选项列表
export const getInstituNameList = (store, params) => {
  return get('/api/getInstituNameList', params)
}
// 新增合作机构（手动输入）
export const addProjPartner = (store, params) => {
  return post('/api/addProjPartner', params)
}
// 新增合作机构 (直接选择)
export const addProjIPartner = (store, params) => {
  return post('/api/addProjIPartner', params)
}
// 更新合作机构
export const updProjPartner = (store, params) => {
  return post('/api/updProjPartner', params)
}
// 删除合作机构
export const delProjPartner = (store, params) => {
  return post('/api/delProjPartner', params)
}

/* ===========交易所=========== */

// 获取交易所列表
export const getProjExchangeList = (store, params) => {
  return post('/api/getProjExchangeList', params)
}
// 获取交易所下拉选项列表
export const getExchangeNameList = (store, params) => {
  return get('/api/getExchangeNameList', params)
}
// 新增交易所（手动输入）
export const addProjExchange = (store, params) => {
  return post('/api/addProjExchange', params)
}
// 新增交易所 (直接选择)
export const addProjIExchange = (store, params) => {
  return post('/api/addProjIExchange', params)
}
// 更新交易所
export const updProjExchange = (store, params) => {
  return post('/api/updProjExchange', params)
}
// 删除交易所
export const delProjExchange = (store, params) => {
  return post('/api/delProjExchange', params)
}

/* ===========媒体报道=========== */

// 获取媒体报道列表
export const getProjReportList = (store, params) => {
  return post('/api/getProjReportList', params)
}
// 获取媒体报道下拉选项列表
export const getMediaList = (store, params) => {
  return get('/api/getMediaList', params)
}
// 新增媒体报道
export const addProjReport = (store, params) => {
  return post('/api/addProjReport', params)
}
// 更新媒体报道
export const updProjReport = (store, params) => {
  return post('/api/updProjReport', params)
}
// 删除媒体报道
export const delProjReport = (store, params) => {
  return post('/api/delProjReport', params)
}

/* ==================余币宝========================= */

/* ===========媒体报道=========== */

// 获取余币宝列表
export const getProjDepositBoxList = (store, params) => {
  return post('/api/getProjDepositBoxList', params)
}
// 获取余币宝下拉项
export const getProjDepositOrderList = (store, params) => {
  return post('/api/getProjDepositOrderList', params)
}
// 新增余币宝信息
export const addDepositBox = (store, params) => {
  return post('/api/addDepositBox', params)
}
// 删除余币宝信息
export const delDepositBox = (store, params) => {
  return post('/api/delDepositBox', params)
}
// 刷新余币宝信息
export const getBoxTxRecordList = (store, params) => {
  return post('/api/getBoxTxRecordList', params)
}
// 确认余币宝信息
export const confirmBoxTx = (store, params) => {
  return post('/api/confirmBoxTx', params)
}

/* ===========媒体报道=========== */
// 获取授权人员
export const getAdminList = (store, params) => {
  return post('/api/getAdminList')
}
// 取消授权
export const cancelOperate = (store, params) => {
  return post('/api/cancelOperate', params)
}
// 授权
export const authOperate = (store, params) => {
  return post('/api/authOperate', params)
}
/* ===========用户人员=========== */
export const getUserList = (store, params) => {
  return post('/api/getUserList', params)
}

export const getUserSearch = (store, params) => {
  return post('/api/getUserSearch', params)
}

// export const authOperate = (store, params) => {
//   return post('/api/authOperate', params)
// }
/* ===========用户人员=========== */

export const inspectCode = (store, params) => {
  return post('/api/inspectCode', params)
}
