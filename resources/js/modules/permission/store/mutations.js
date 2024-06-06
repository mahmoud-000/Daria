export const SET_PERMISSIONS = (state, permissions) => {
  state.permissions = permissions
}

export const SET_PERMISSION = (state, permission) => {
  state.permission = permission
}

export const SET_OPTIONS = (state, options) => {
  state.options = options
}

export const SET_PAGINATION = (state, {meta, options}) => {
  state.pagination.page = meta.current_page
  state.pagination.rowsPerPage = meta.per_page
  state.pagination.rowsNumber = meta.total
  state.pagination.sortBy = options.sortBy
  state.pagination.descending = options.descending
}