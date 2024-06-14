import { Cookies } from 'quasar'
import currencies from '../../../utils/currencies'

export const getCompanies = (state) => state.companies
export const getCompany = (state) => state.company
export const getPagination = (state) => state.pagination
export const getOptions = (state) => state.options
export const getCurrency = (state) => state.currency ?? Cookies.get('currency') ?? 'USD'
export const getDefaultCurrencySymbol = (state, { getCurrency }) => currencies.find(currency => currency.code === getCurrency).symbol ?? '$'