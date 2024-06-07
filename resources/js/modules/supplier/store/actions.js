import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import Supplier from '../../../models/Supplier'
const SupplierModel = new Supplier('suppliers')

export const fetchSuppliers = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_SUPPLIERS', [])
    return new Promise((resolve, reject) => {
        SupplierModel.list(options).then(res => {
            commit('SET_SUPPLIERS', res.data)
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

export const fetchSupplier = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        SupplierModel.get(id).then(res => {
            commit('SET_SUPPLIER', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createSupplier = async (_, supplier) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        SupplierModel.store(supplier).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateSupplier = async (_, supplier) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        SupplierModel.update(supplier.id, supplier).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroySupplier = (_, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        SupplierModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroySuppliers = (_, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        SupplierModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, suppliers) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        SupplierModel.importCSV(suppliers).then(res => {
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

export const register = async (_, supplier) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        SupplierModel.register(supplier).then(res => {
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
        SupplierModel.options(query).then(res => {
            commit('SET_OPTIONS', res.data)
            commit('SET_META', res.meta)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};