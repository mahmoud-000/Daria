export const SET_QUOTATION = (state, quotations) => {
  state.quotations = quotations
}

export const SET_PURCHASE = (state, quotation) => {
  state.quotation = quotation
}

export const SET_PAGINATION = (state, { meta, options }) => {
  state.pagination.page = meta.current_page
  state.pagination.rowsPerPage = meta.per_page
  state.pagination.rowsNumber = meta.total
  state.pagination.sortBy = options.sortBy
  state.pagination.descending = options.descending
}

export const SET_OPTIONS = (state, options) => {
  state.options = options
}
