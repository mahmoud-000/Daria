import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Variant from '../../../models/Variant'
const VariantModel = new Variant()

export const fetchVariants = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_CATEGORIES', [])
    return new Promise((resolve, reject) => {
        VariantModel.list(options).then(res => {
            Loading.hide();
            commit('SET_CATEGORIES', res.data)
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

export const fetchVariant = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        VariantModel.get(id).then(res => {
            commit('SET_CATEGORY', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createVariant = async ({ dispatch }, variant) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        VariantModel.store(variant).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateVariant = async ({ dispatch }, variant) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        VariantModel.update(variant.id, variant).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyVariant = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        VariantModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyVariants = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        VariantModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, variants) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        VariantModel.importCSV(variants).then(res => {
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

export const fetchOptions = ({ commit }, query) => {
    return new Promise((resolve, reject) => {
        VariantModel.options(query).then(res => {
            commit('SET_OPTIONS', res.data)
            commit('SET_META', res.meta)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const exportPdf = ({ commit, state }, id) => {
    fireLoadingSpinner('exporting_pdf')
    return new Promise((resolve, reject) => {
        VariantModel.exportPDF(id).then(res => {
            const cert = state.variants.find(cert => cert.id == id);
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