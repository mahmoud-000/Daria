import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Attribute from '../../../models/Attribute'
const AttributeModel = new Attribute()

export const fetchAttributes = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_WAREHOUSES', [])
    return new Promise((resolve, reject) => {
        AttributeModel.list(options).then(res => {
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

export const fetchAttribute = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        AttributeModel.get(id).then(res => {
            commit('SET_WAREHOUSE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createAttribute = async ({ dispatch }, attribute) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        AttributeModel.store(attribute).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const updateAttribute = async ({ dispatch }, attribute) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        AttributeModel.update(attribute.id, attribute).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const destroyAttribute = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        AttributeModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const bulkDestroyAttributes = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        AttributeModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response.data.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, attributes) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        AttributeModel.importCSV(attributes).then(res => {
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
        AttributeModel.options(query).then(res => {
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
        AttributeModel.exportPDF(id).then(res => {
            const cert = state.attributes.find(cert => cert.id == id);
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