import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import Customer from '../../../models/Customer'
const CustomerModel = new Customer('customers')

export const fetchCustomers = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_CUSTOMERS', [])
    return new Promise((resolve, reject) => {
        CustomerModel.list(options).then(res => {
            commit('SET_CUSTOMERS', res.data)
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

export const fetchCustomer = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        CustomerModel.get(id).then(res => {
            commit('SET_CUSTOMER', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createCustomer = async (_, customer) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        CustomerModel.store(customer).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const updateCustomer = async (_, customer) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        CustomerModel.update(customer.id, customer).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const destroyCustomer = (_, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        CustomerModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const bulkDestroyCustomers = (_, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        CustomerModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, customers) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        CustomerModel.importCSV(customers).then(res => {
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

export const register = async (_, customer) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        CustomerModel.register(customer).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const fetchOptions = ({ commit }) => {
    return new Promise((resolve, reject) => {
        CustomerModel.options().then(res => {
            commit('SET_OPTIONS', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};