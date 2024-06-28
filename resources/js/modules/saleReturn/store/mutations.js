export const SET_PURCHASES = (state, saleReturns) => {
  state.saleReturns = saleReturns
}

export const SET_PURCHASE = (state, saleReturn) => {
  state.saleReturn = saleReturn
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
