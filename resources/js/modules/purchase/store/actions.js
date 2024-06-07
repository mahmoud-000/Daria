import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Purchase from '../../../models/Purchase'
const PurchaseModel = new Purchase()

export const fetchPurchases = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_PURCHASES', [])
    return new Promise((resolve, reject) => {
        PurchaseModel.list(options).then(res => {
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

export const fetchPurchase = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        PurchaseModel.get(id).then(res => {
            commit('SET_PURCHASE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createPurchase = async ({ dispatch }, purchase) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        PurchaseModel.store(purchase).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updatePurchase = async ({ dispatch }, purchase) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        PurchaseModel.update(purchase.id, purchase).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyPurchase = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        PurchaseModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyPurchases = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        PurchaseModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, purchases) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        PurchaseModel.importCSV(purchases).then(res => {
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
        PurchaseModel.exportPDF(id).then(res => {
            const cert = state.purchases.find(cert => cert.id == id);
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
        PurchaseModel.formOptions(query).then(res => {
            commit('supplier/SET_OPTIONS', res.suppliers, { root: true})
            commit('delegate/SET_OPTIONS', res.delegates, { root: true})
            commit('warehouse/SET_OPTIONS', res.warehouses, { root: true})
            commit('unit/SET_OPTIONS', res.units, { root: true})
            commit('pipeline/SET_OPTIONS', res.pipelines, { root: true})
            commit('setting/SET_SYSTEM_SETTINGS', res.settings, { root: true})
            commit('setting/SET_SYSTEM_CURRENCY', null, { root: true})
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};