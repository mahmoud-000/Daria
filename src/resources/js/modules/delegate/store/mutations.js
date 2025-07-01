export const SET_DELEGATES = (state, delegates) => {
  state.delegates = delegates
}

export const SET_DELEGATE = (state, delegate) => {
  state.delegate = delegate
}
export const SET_PAGINATION = (state, {meta, options}) => {
  state.pagination.page = meta.current_page
  state.pagination.rowsPerPage = meta.per_page
  state.pagination.rowsNumber = meta.total
  state.pagination.sortBy = options.sortBy
  state.pagination.descending = options.descending
}

export const SET_META = (state, meta) => {
  state.meta.current_page = meta.current_page
  state.meta.last_page = meta.last_page
}

export const SET_OPTIONS = (state, options) => {
  state.options = options
}