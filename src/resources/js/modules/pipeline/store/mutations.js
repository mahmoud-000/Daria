export const SET_PIPELINES = (state, pipelines) => {
  state.pipelines = pipelines
}

export const SET_PIPELINE = (state, pipeline) => {
  state.pipeline = pipeline
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
