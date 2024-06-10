export const SET_WAREHOUSES = (state, attributes) => {
  state.attributes = attributes
}

export const SET_WAREHOUSE = (state, attribute) => {
  state.attribute = attribute
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
