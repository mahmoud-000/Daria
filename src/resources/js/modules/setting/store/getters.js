import { Cookies } from 'quasar'
import currencies from '../../../utils/currencies'

const APP_NAME = import.meta.env.VITE_APP_NAME
const APP_URL = import.meta.env.VITE_APP_URL

export const getSystemSettings = (state) => state.system_settings
export const getSystemName = (state) => state.system_name ?? Cookies.get('system_name') ?? APP_NAME
export const getDefaultCurrency = (state) => state.default_currency ?? Cookies.get('default_currency') ?? 'USD'
export const getDefaultCurrencySymbol = (state, { getDefaultCurrency }) => currencies.find(currency => currency.code === getDefaultCurrency).symbol ?? '$'
export const getSystemLogo = (state) => state.system_logo ?? Cookies.get('system_logo')
export const getDefaultSystemLogo = () => ({
  url: APP_URL + "/modules/upload/img/no-image.png",
  original_url: APP_URL + "/modules/upload/img/no-image.png",
  filename: "no-image.png",
  mime_type: "image/png",
  fake: true,
})
