import Upload from '../../../models/Upload'
import { fireErrorNotify, fireLoadingSpinner, fireSuccessNotify } from '../../../utils/notify';
const UploadModel = new Upload('uploads')
import i18n from "../../../i18n";
const { t } = i18n.global

export const upload = async ({ commit }, upload) => {
    fireLoadingSpinner('uploading_file')
    return new Promise((resolve, reject) => {
        UploadModel.upload(upload).then(res => {
            commit("SET_FILE", res.payload)
            fireSuccessNotify(res, t('messages.uploaded_successfully'))
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const reorder = async (_, files) => {
    fireLoadingSpinner('reordering_file')
    return new Promise((resolve, reject) => {
        UploadModel.reorder(files).then(res => {
            fireSuccessNotify(res, t('messages.reordered_successfully'))
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};

export const destroy = async ({ commit }, fileDestroy) => {
    fireLoadingSpinner('deleting_file')
    return new Promise((resolve, reject) => {
        UploadModel.destroy(fileDestroy).then((res) => {
            fireSuccessNotify(res, t('messages.deleted_successfully'))
            if (fileDestroy.collection === 'system_logo') {
                commit('setting/DESTROY_SYSTEM_LOGO', null, { root: true })
            }
            resolve(res);
        })
            .catch(error => {
                fireErrorNotify(error)
                reject(error);
            });
    })
};