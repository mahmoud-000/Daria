export const SET_USERS = (state, users) => {
  state.users = users
}

export const SET_USER = (state, user) => {
  state.user = user
}
export const SET_PAGINATION = (state, {meta, options}) => {
  state.pagination.page = meta.current_page
  state.pagination.rowsPerPage = meta.per_page
  state.pagination.rowsNumber = meta.total
  state.pagination.sortBy = options.sortBy
  state.pagination.descending = options.descending
}
