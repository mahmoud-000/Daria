export const SET_STOCKS = (state, stocks) => {
  state.stocks = stocks
}

export const SET_STOCK = (state, stock) => {
  state.stock = stock
}

export const SET_STOCK_BY_WAREHOUSE = (state, stockByWarehouse) => {
  state.stockByWarehouse = stockByWarehouse
}

export const SET_PAGINATION = (state, { meta, options }) => {
  state.pagination.page = meta.current_page
  state.pagination.rowsPerPage = meta.per_page
  state.pagination.rowsNumber = meta.total
  state.pagination.sortBy = options?.sortBy
  state.pagination.descending = options?.descending
}

export const SET_META = (state, meta) => {
  state.meta.current_page = meta.current_page
  state.meta.last_page = meta.last_page
}