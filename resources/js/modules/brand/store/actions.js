import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Brand from '../../../models/Brand'
const BrandModel = new Brand()

export const fetchBrands = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_BRANDS', [])
    return new Promise((resolve, reject) => {
        BrandModel.list(options).then(res => {
            Loading.hide();
            commit('SET_BRANDS', res.data)
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

export const fetchBrand = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        BrandModel.get(id).then(res => {
            commit('SET_BRAND', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createBrand = async ({ dispatch }, brand) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        BrandModel.store(brand).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateBrand = async ({ dispatch }, brand) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        BrandModel.update(brand.id, brand).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyBrand = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        BrandModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyBrands = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        BrandModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, brands) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        BrandModel.importCSV(brands).then(res => {
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
        BrandModel.options(query).then(res => {
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
        BrandModel.exportPDF(id).then(res => {
            const cert = state.brands.find(cert => cert.id == id);
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