import Resource from "../../../models/resource";
const ResourceLocale = new Resource("locales");

export const setLocale = ({ commit }, locale) => {
    commit('SET_LOCALE', locale)
    return new Promise((resolve, reject) => {
        ResourceLocale.store({ locale: locale })
            .then(response => {
                commit('SET_LOCALE', response.locale)
                resolve(response);
            })
            .catch(error => {
                reject(error);
            });
    });
};


export const fetchLocales = ({ commit }) => {
    return new Promise((resolve, reject) => {
        ResourceLocale.list()
            .then(response => {
                commit('SET_LOCALES', response.locales)
                resolve(response);
            })
            .catch(error => {
                reject(error);
            });
    });
};
