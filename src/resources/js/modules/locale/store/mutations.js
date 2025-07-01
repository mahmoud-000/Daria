import { Cookies } from "quasar";

export const SET_LOCALE = (state, locale) => {
    state.locale = locale;
    Cookies.set("locale", locale, {
        path: '/'
    });
};

export const SET_LOCALES = (state, locales) => {
    state.locales = locales;
    Cookies.set("locales", JSON.stringify(locales), {
        path: '/'
    });
};
