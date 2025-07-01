import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import Delegate from '../../../models/Delegate'
const DelegateModel = new Delegate('delegates')

export const fetchDelegates = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_DELEGATES', [])
    return new Promise((resolve, reject) => {
        DelegateModel.list(options).then(res => {
            commit('SET_DELEGATES', res.data)
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

export const fetchDelegate = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        DelegateModel.get(id).then(res => {
            commit('SET_DELEGATE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createDelegate = async (_, delegate) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        DelegateModel.store(delegate).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updateDelegate = async (_, delegate) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        DelegateModel.update(delegate.id, delegate).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyDelegate = (_, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        DelegateModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const bulkDestroyDelegates = (_, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        DelegateModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, delegates) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        DelegateModel.importCSV(delegates).then(res => {
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

export const register = async (_, delegate) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        DelegateModel.register(delegate).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const fetchOptions = ({ commit }, query) => {
    return new Promise((resolve, reject) => {
        DelegateModel.options(query).then(res => {
            commit('SET_OPTIONS', res.data)
            commit('SET_META', res.meta)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};