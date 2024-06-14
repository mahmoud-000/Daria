import { Cookies } from 'quasar'

export const SET_PIPELINES = (state, companies) => {
  state.companies = companies
}

export const SET_PIPELINE = (state, company) => {
  state.company = company
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

export const SET_CURRENCY = (state, currency) => {
  state.currency = currency;

  if (state.currency) {
    Cookies.set('currency', state.currency, {
      path: '/'
    })
  }
}
