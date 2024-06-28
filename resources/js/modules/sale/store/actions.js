import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Sale from '../../../models/Sale'
const SaleModel = new Sale()

export const fetchSales = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_PURCHASES', [])
    return new Promise((resolve, reject) => {
        SaleModel.list(options).then(res => {
            Loading.hide();
            commit('SET_PURCHASES', res.data)
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

export const fetchSale = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        SaleModel.get(id).then(res => {
            commit('SET_PURCHASE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createSale = async ({ dispatch }, sale) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        SaleModel.store(sale).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updateSale = async ({ dispatch }, sale) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        SaleModel.update(sale.id, sale).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroySale = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        SaleModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const bulkDestroySales = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        SaleModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, sales) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        SaleModel.importCSV(sales).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                commit('SET_ERROR', error.response?.data?.payload.errors, { root: true })
                Loading.hide();
                reject(error);
            });
    })
};

export const exportPdf = ({ commit, state }, id) => {
    fireLoadingSpinner('exporting_pdf')
    return new Promise((resolve, reject) => {
        SaleModel.exportPDF(id).then(res => {
            const cert = state.sales.find(cert => cert.id == id);
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
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const fetchFormOptions = ({ commit }, query) => {
    return new Promise((resolve, reject) => {
        SaleModel.formOptions(query).then(res => {
            commit('customer/SET_OPTIONS', res.customers, { root: true})
            commit('delegate/SET_OPTIONS', res.delegates, { root: true})
            commit('warehouse/SET_OPTIONS', res.warehouses, { root: true})
            commit('unit/SET_OPTIONS', res.units, { root: true})
            commit('pipeline/SET_OPTIONS', res.pipelines, { root: true})
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};