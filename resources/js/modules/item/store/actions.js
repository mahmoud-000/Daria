import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Item from '../../../models/Item'
const ItemModel = new Item()

export const fetchItems = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_PRODUCTS', [])
    return new Promise((resolve, reject) => {
        ItemModel.list(options).then(res => {
            Loading.hide();
            commit('SET_PRODUCTS', res.data)
            commit('SET_PAGINATION', { meta: res.meta, options })
            commit('SET_LOADING', false, { root: true })
            resolve(res);
        })
            .catch(error => {
                commit('SET_LOADING', false, { root: true })
                reject(error);
            });
    })
};

export const fetchItem = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        ItemModel.get(id).then(res => {
            commit('SET_PRODUCT', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createItem = async ({ dispatch }, item) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        ItemModel.store(item).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateItem = async ({ dispatch }, item) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        ItemModel.update(item.id, item).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyItem = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        ItemModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyItems = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        ItemModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const fetchOptions = ({ commit }, query) => {
    return new Promise((resolve, reject) => {
        ItemModel.options(query).then(res => {
            commit('SET_OPTIONS', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, items) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        ItemModel.importCSV(items).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                commit('SET_ERROR', error.response.data.payload.errors, { root: true })
                Loading.hide();
                reject(error);
            });
    })
};

export const exportPdf = ({ commit, state }, id) => {
    fireLoadingSpinner('exporting_pdf')
    return new Promise((resolve, reject) => {
        ItemModel.exportPDF(id).then(res => {
            const cert = state.items.find(cert => cert.id == id);
            const url = window.URL.createObjectURL(new Blob([res]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", `${cert.serial_number}.pdf`);
            document.body.appendChild(link);
            link.click();
            fireSuccessNotify(res, t('messages.downloaded_successfully'))
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const fetchFormOptions = ({ commit }, query) => {
    return new Promise((resolve, reject) => {
        ItemModel.formOptions(query).then(res => {
            commit('category/SET_OPTIONS', res.categories, { root: true})
            commit('brand/SET_OPTIONS', res.brands, { root: true})
            commit('unit/SET_OPTIONS', res.units, { root: true})
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};