import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import Role from '../../../models/Role'
const RoleModel = new Role()

export const fetchRoles = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_ROLES', [])
    return new Promise((resolve, reject) => {
        RoleModel.list(options).then(res => {
            commit('SET_ROLES', res.data)
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

export const fetchRole = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        RoleModel.get(id).then(res => {
            commit('SET_ROLE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createRole = async ({ dispatch }, role) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        RoleModel.store(role).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateRole = async ({ dispatch }, role) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        RoleModel.update(role.id, role).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyRole = ({ commit }, id) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        RoleModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyRoles = ({ commit }, ids) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        RoleModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, roles) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        RoleModel.importCSV(roles).then(res => {
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
        RoleModel.options(query).then(res => {
            commit('SET_OPTIONS', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};
