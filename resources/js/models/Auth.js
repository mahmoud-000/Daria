import request from "../utils/request";

export function login(data) {
    return request({
        url: "/auth/login",
        method: "post",
        data: data
    });
}

export function changePassword(data) {
    return request({
        url: "/auth/change_password",
        method: "post",
        data: data
    });
}

export function forgetPassword(data) {
    return request({
        url: "/auth/forget_password",
        method: "post",
        data: data
    });
}

export function resetPassword(data) {
    return request({
        url: "/auth/reset_password",
        method: "post",
        data: data
    });
}

export function logout() {
    return request({
        url: "/auth/logout",
        method: "post"
    });
}

export function profileUser() {
    return request({
        url: "/auth/profile/user",
        method: "get"
    });
}

export function profileCustomer() {
    return request({
        url: "/auth/profile/customer",
        method: "get"
    });
}

export function profileUpdateUser(data) {
    return request({
        url: "/auth/profile/user",
        method: "post",
        data: data
    });
}

export function profileUpdateCustomer(data) {
    return request({
        url: "/auth/profile/customer",
        method: "post",
        data: data
    });
}

export function permissions() {
    return request({
        url: "/auth/permissions",
        method: "get"
    });
}
