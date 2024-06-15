export const SET_BRANDS = (state, jobs) => {
  state.jobs = jobs
}

export const SET_BRAND = (state, job) => {
  state.job = job
}

export const SET_PAGINATION = (state, { meta, options }) => {
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
