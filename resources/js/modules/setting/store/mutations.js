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

export const SET_SYSTEM_CURRENCY = (state) => {
  state.system_currency = state.system_settings.find((setting) => setting.key === 'currency')?.value;

  if (state.system_currency) {
    Cookies.set('system_currency', state.system_currency, {
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