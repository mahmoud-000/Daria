import { Cookies, LocalStorage } from "quasar";

const TokenName = "TOKEN";
const Permissions = "PERMISSIONS";
const LoginUser = "LOGIN_USER";

export function setPermissions(permissions) {
    return LocalStorage.set(Permissions, permissions);
}

export function getPermissions() {
    return LocalStorage.getItem(Permissions);
}

export function removePermissions() {
    return LocalStorage.remove(Permissions);
}

export function setToken(token) {
    return Cookies.set(TokenName, token, {
        path: '/'
    });
}

export function getToken() {
    return Cookies.get(TokenName);
}

export function removeToken() {
    return Cookies.remove(TokenName, {
        path: '/'
    });
}

export function setLoginUser(loginUser) {
    return Cookies.set(LoginUser, JSON.stringify(loginUser), {
        path: '/'
    });
}

export function getLoginUser() {
    return Cookies.get(LoginUser);
}

export function removeLoginUser() {
    return Cookies.remove(LoginUser, {
        path: '/'
    });
}