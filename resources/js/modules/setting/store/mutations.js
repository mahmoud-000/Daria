import { Cookies } from 'quasar'

export const SET_SYSTEM_SETTINGS = (state, system_settings) => {
  state.system_settings = system_settings
}

export const SET_SYSTEM_NAME = (state) => {
  state.system_name = state.system_settings.find((setting) => setting.key === 'system_name')?.value;

  if (state.system_name) {
    Cookies.set('system_name', state.system_name, {
      path: '/'
    })
  }
}

export const SET_DEFAULT_CURRENCY = (state) => {
  state.default_currency = state.system_settings.find((setting) => setting.key === 'default_currency')?.value;

  if (state.default_currency) {
    Cookies.set('default_currency', state.default_currency, {
      path: '/'
    })
  }
}

export const SET_SYSTEM_LOGO = async (state) => {
  let logo = await state.system_settings.find((setting) => setting.key === 'system_logo')?.value;

  if (!logo.fake) {
    state.system_logo = logo.url;
    Cookies.set('system_logo', logo.url, {
      path: '/'
    })
  }
}

export const DESTROY_SYSTEM_LOGO = (state) => {
  state.system_logo = null

  Cookies.remove('system_logo', {
    path: '/'
  })

}