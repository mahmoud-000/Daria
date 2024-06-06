import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Warehouse from '../../../models/Warehouse'
const WarehouseModel = new Warehouse()

export const fetchWarehouses = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_WAREHOUSES', [])
    return new Promise((resolve, reject) => {
        WarehouseModel.list(options).then(res => {
            Loading.hide();
            commit('SET_WAREHOUSES', res.data)
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

export const fetchWarehouse = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        WarehouseModel.get(id).then(res => {
            commit('SET_WAREHOUSE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createWarehouse = async ({ dispatch }, warehouse) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        WarehouseModel.store(warehouse).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const updateWarehouse = async ({ dispatch }, warehouse) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        WarehouseModel.update(warehouse.id, warehouse).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const destroyWarehouse = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        WarehouseModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const bulkDestroyWarehouses = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        WarehouseModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, warehouses) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        WarehouseModel.importCSV(warehouses).then(res => {
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

export const fetchOptions = ({ commit }) => {
    return new Promise((resolve, reject) => {
        WarehouseModel.options().then(res => {
            commit('SET_OPTIONS', res.data)
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
        WarehouseModel.exportPDF(id).then(res => {
            const cert = state.warehouses.find(cert => cert.id == id);
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
                fireErrorNotify(error)
                reject(error);
            });
    })
};