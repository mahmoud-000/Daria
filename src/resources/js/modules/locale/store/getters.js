import { Cookies } from "quasar"

export const getLocale = (state) => state.locale ?? Cookies.get('locale') ?? 'en'
export const getLocales = (state) => state.locales ?? Cookies.get('locales') ?? ['en', 'ar']