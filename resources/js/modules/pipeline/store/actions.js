import { Loading } from "quasar";
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';

import i18n from "../../../i18n";
const { t } = i18n.global

import Pipeline from '../../../models/Pipeline'
const PipelineModel = new Pipeline()

export const fetchPipelines = ({ commit }, options) => {
    commit('SET_LOADING', true, { root: true })
    // commit('SET_PIPELINES', [])
    return new Promise((resolve, reject) => {
        PipelineModel.list(options).then(res => {
            Loading.hide();
            commit('SET_PIPELINES', res.data)
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

export const fetchPipeline = ({ commit }, id) => {
    return new Promise((resolve, reject) => {
        PipelineModel.get(id).then(res => {
            commit('SET_PIPELINE', res.data)
            resolve(res);
        })
            .catch(error => {
                reject(error);
            });
    })
};

export const createPipeline = async ({ dispatch }, pipeline) => {
    fireLoadingSpinner('creating_module')
    return new Promise((resolve, reject) => {
        PipelineModel.store(pipeline).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const updatePipeline = async ({ dispatch }, pipeline) => {
    fireLoadingSpinner('updating_module')
    return new Promise((resolve, reject) => {
        PipelineModel.update(pipeline.id, pipeline).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const destroyPipeline = ({ commit }, id) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        PipelineModel.destroy(id).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const bulkDestroyPipelines = ({ commit }, ids) => {
    fireLoadingSpinner('deleting_module')
    return new Promise((resolve, reject) => {
        PipelineModel.bulk_destroy(ids).then(res => {
            fireSuccessNotify(res)
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error, error.response?.data?.payload)
                reject(error);
            });
    })
};

export const importCsv = async ({ commit }, pipelines) => {
    fireLoadingSpinner('importing_module')
    return new Promise((resolve, reject) => {
        PipelineModel.importCSV(pipelines).then(res => {
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
        PipelineModel.options(query).then(res => {
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
        PipelineModel.exportPDF(id).then(res => {
            const cert = state.pipelines.find(cert => cert.id == id);
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