import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Department from '../../../models/Department'
const DepartmentModel = new Department()

export const fetchDepartments = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_CATEGORIES', [])
    return new Promise((resolve, reject) => {
        DepartmentModel.list(options).then(res => {
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

export const fetchDepartment = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        DepartmentModel.get(id).then(res => {
            commit('SET_CATEGORY', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createDepartment = async ({ dispatch }, department) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        DepartmentModel.store(department).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateDepartment = async ({ dispatch }, department) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        DepartmentModel.update(department.id, department).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyDepartment = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        DepartmentModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyDepartments = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        DepartmentModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, departments) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        DepartmentModel.importCSV(departments).then(res => {
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
        DepartmentModel.options(query).then(res => {
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
        DepartmentModel.exportPDF(id).then(res => {
            const cert = state.departments.find(cert => cert.id == id);
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