import { Cookies } from "quasar";

const Settings = "settings";

export function removeLocale() {
    return Cookies.remove(LocaleKey, {
        path: '/'
    });
}

export function setSettings(settings) {
    return Cookies.set(Settings, JSON.stringify(settings), {
        path: '/'
    });
}

export function getSettings() {
    return Cookies.get(Settings);
}

export function removeSettings() {
    return Cookies.remove(Settings, {
        path: '/'
    });
}