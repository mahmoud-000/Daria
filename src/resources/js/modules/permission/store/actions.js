import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import Permission from '../../../models/Permission'
const PermissionModel = new Permission('permissions')

export const fetchPermissions = ({ commit }, options) => {
    return new Promise((resolve, reject) => {
        PermissionModel.list(options).then(res => {
            commit('SET_PERMISSIONS', res.data)
            commit('SET_PAGINATION', { meta: res.meta, options })
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const fetchPermission = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        PermissionModel.get(id).then(res => {
            commit('SET_PERMISSION', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createPermission = async ({ dispatch }, permission) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        PermissionModel.store(permission).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updatePermission = async ({ dispatch }, permission) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        PermissionModel.update(permission.id, permission).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyPermission = ({ commit }, id) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        PermissionModel.destroy(id).then(res => {
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
        PermissionModel.options(query).then(res => {
            commit('SET_OPTIONS', res)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};