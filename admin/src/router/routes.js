const getView = path => {
  return () => import(`@/views/${path}.vue`).catch(e => location.reload())
}

const getComponent = path => {
  return () => import(`@/components/${path}.vue`).catch(e => location.reload())
}

export default [{
  path: '/',
  component: getComponent('layout/layout'),
  meta: {requiresAuth: true},
  redirect: '/project',
  children: [{ // 项目信息
    path: 'project',
    component: getView('project/project'),
    meta: {requiresAuth: true},
    redirect: '/project/info',
    children: [{ // 基本信息
      path: 'info',
      component: getView('project/info'),
      meta: {requiresAuth: true}
    }, { // 团队成员
      path: 'team',
      component: getView('project/team'),
      meta: {requiresAuth: true}
    }, { // 发展历程
      path: 'process',
      component: getView('project/process'),
      meta: {requiresAuth: true}
    }, { // 社交信息
      path: 'social',
      component: getView('project/social'),
      meta: {requiresAuth: true}
    }, { // 项目顾问
      path: 'adviser',
      component: getView('project/adviser'),
      meta: {requiresAuth: true}
    }, { // 投资机构
      path: 'institution',
      component: getView('project/institution'),
      meta: {requiresAuth: true}
    }, { // 交易所
      path: 'exchange',
      component: getView('project/exchange'),
      meta: {requiresAuth: true}
    }, { // 媒体报道
      path: 'news',
      component: getView('project/news'),
      meta: {requiresAuth: true}
    }]
  }, { // 余币宝
    path: 'candy',
    component: getView('candy/candy'),
    meta: {requiresAuth: true},
    redirect: '/candy/list',
    children: [{ // 余币宝列表
      path: 'list',
      component: getView('candy/list'),
      meta: {requiresAuth: true}
    }, { // 订单列表
      path: 'order',
      component: getView('candy/order'),
      meta: {requiresAuth: true}
    }]
  }]
}, {
  path: '/signin',
  component: getView('sign/signin')
}, {
  path: '*',
  redirect: '/'
}]

