import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import PurchaseReturn from '../../../models/PurchaseReturn'
const PurchaseReturnModel = new PurchaseReturn()

export const fetchPurchaseReturns = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_PURCHASES', [])
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.list(options).then(res => {
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

export const fetchPurchaseReturn = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.get(id).then(res => {
            commit('SET_PURCHASE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createPurchaseReturn = async ({ dispatch }, purchaseReturn) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.store(purchaseReturn).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updatePurchaseReturn = async ({ dispatch }, purchaseReturn) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.update(purchaseReturn.id, purchaseReturn).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyPurchaseReturn = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const bulkDestroyPurchaseReturns = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, purchaseReturns) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        PurchaseReturnModel.importCSV(purchaseReturns).then(res => {
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
        PurchaseReturnModel.exportPDF(id).then(res => {
            const cert = state.purchaseReturns.find(cert => cert.id == id);
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
        PurchaseReturnModel.formOptions(query).then(res => {
            commit('supplier/SET_OPTIONS', res.suppliers, { root: true})
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