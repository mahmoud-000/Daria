import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Branch from '../../../models/Branch'
const BranchModel = new Branch()

export const fetchBranches = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_CATEGORIES', [])
    return new Promise((resolve, reject) => {
        BranchModel.list(options).then(res => {
            Loading.hide();
            commit('SET_CATEGORIES', res.data)
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

export const fetchBranch = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        BranchModel.get(id).then(res => {
            commit('SET_CATEGORY', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createBranch = async ({ dispatch }, branch) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        BranchModel.store(branch).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updateBranch = async ({ dispatch }, branch) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        BranchModel.update(branch.id, branch).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyBranch = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        BranchModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const bulkDestroyBranches = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        BranchModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, branches) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        BranchModel.importCSV(branches).then(res => {
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

export const fetchOptions = ({ commit }, query) => {
    return new Promise((resolve, reject) => {
        BranchModel.options(query).then(res => {
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
        BranchModel.exportPDF(id).then(res => {
            const cert = state.branches.find(cert => cert.id == id);
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