import { getLoginUser, getPermissions } from '../../../utils/auth'

export const getProfile = (state) => state.profile
export const LoginUser = (state) => state.loginUser ?? getLoginUser()
export const Permissions = (state) => state.permissions ?? getPermissions()