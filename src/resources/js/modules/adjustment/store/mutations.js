export const SET_PURCHASES = (state, adjustments) => {
  state.adjustments = adjustments
}

export const SET_PURCHASE = (state, adjustment) => {
  state.adjustment = adjustment
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
