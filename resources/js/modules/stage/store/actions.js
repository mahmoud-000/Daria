import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Stage from '../../../models/Stage'
const StageModel = new Stage()

export const fetchStages = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_CATEGORIES', [])
    return new Promise((resolve, reject) => {
        StageModel.list(options).then(res => {
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

export const fetchStage = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        StageModel.get(id).then(res => {
            commit('SET_CATEGORY', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createStage = async ({ dispatch }, stage) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        StageModel.store(stage).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updateStage = async ({ dispatch }, stage) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        StageModel.update(stage.id, stage).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyStage = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        StageModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const bulkDestroyStages = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        StageModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, stages) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        StageModel.importCSV(stages).then(res => {
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
        StageModel.options(query).then(res => {
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
        StageModel.exportPDF(id).then(res => {
            const cert = state.stages.find(cert => cert.id == id);
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