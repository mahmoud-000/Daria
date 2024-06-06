import { Cookies } from 'quasar'
import currencies from '../../../utils/currencies'

const APP_NAME = import.meta.env.VITE_APP_NAME
const APP_URL = import.meta.env.VITE_APP_URL

export const getSystemSettings = (state) => state.system_settings
export const getSystemName = (state) => state.system_name ?? Cookies.get('system_name') ?? APP_NAME
export const getSystemCurrency = (state) => state.system_currency ?? Cookies.get('system_currency') ?? 'USD'
export const getSystemCurrencySymbol = (state, { getSystemCurrency }) => currencies.find(currency => currency.code === getSystemCurrency).symbol ?? '$'
export const getSystemLogo = (state) => state.system_logo ?? Cookies.get('system_logo')
export const getDefaultSystemLogo = () => ({
  url: APP_URL + "/modules/upload/img/no-image.png",
  original_url: APP_URL + "/modules/upload/img/no-image.png",
  filename: "no-image.png",
  mime_type: "image/png",
  fake: true,
})
