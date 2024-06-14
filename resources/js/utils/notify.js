import { Loading, Notify, QSpinnerFacebook } from "quasar";
import i18n from "../i18n";
const { t } = i18n.global

export const fireLoadingSpinner = (msg) => {
    Loading.show({
        spinner: QSpinnerFacebook,
        spinnerColor: "yellow",
        spinnerSize: 140,
        backgroundColor: "primary",
        message: t(`messages.${msg}`),
        messageColor: "yellow",
    });
}

export const fireSuccessNotify = (res, msg = null) => {
    Loading.hide();
    Notify.create({
        progress: true,
        type: "positive",
        message: msg ?? res.payload,
    });
}

export const fireErrorNotify = (error, msg = null) => {
    Loading.hide();
    let [key, messages] = [];
    if (error.response && error.response.status === 422) {
        [key, messages] = Object.entries(error.response?.data?.payload)[0];
    }

    Notify.create({
        progress: true,
        type: "negative",
        message: msg && error.response.status !== 422
            ? msg
            : error.response.status === 403
                ? error.response?.data?.message
                : messages[0],
    });
}

export const fireNotify = (type, msg) => {
    Notify.create({
        progress: true,
        type: type,
        message: msg,
    });
}