import { setLoginUser, setToken, removeToken, removeLoginUser, setPermissions, removePermissions } from '../../../utils/auth';

export const SET_LOGIN_USER = (state, user) => {
  state.loginUser = user
  setLoginUser(user)
}

export const SET_TOKEN = (state, token) => {
  state.token = token
  setToken(token)
}

export const SET_PERMISSIONS = (state, permissions) => {
  state.permissions = permissions
  setPermissions(permissions)
}

export const REMOVE_AUTH_DETAILS = async (state) => {
  state.loginUser = null
  state.token = null
  await removeLoginUser()
  await removeToken()
  await removePermissions()
}

export const SET_PROFILE = (state, profile) => {
  state.profile = profile
}