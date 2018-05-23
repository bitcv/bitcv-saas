export const updateUserInfo = (state, payload) => {
  let authUserInfo = localStorage.getItem('authUserInfo')

  if (authUserInfo) {
    authUserInfo = JSON.parse(authUserInfo)
  }

  if (payload) {
    authUserInfo = payload
    localStorage.setItem('authUserInfo', JSON.stringify(authUserInfo))
  }

  state.authUserInfo = authUserInfo
}

export const cleanUserInfo = (state) => {
  localStorage.removeItem('authUserInfo')

  state.authUserInfo = null
}
