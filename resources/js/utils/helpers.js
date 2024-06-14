import { computed } from 'vue'
import router from '../router';
import store from '../store';
import { getLoginUser } from './auth';
import { formatNumber } from '../filters'

export const getDefaultCurrencySymbol = computed(
    () => store && store.getters["setting/getDefaultCurrencySymbol"]
);

export const numberFormatWithCurrency = (number = 0, dec = 2) => {
    return getDefaultCurrencySymbol.value + ' ' + formatNumber(number, dec)
}

export const addTo = (array, key, value) => {
    array[key].push({ ...value });
}

export const removeFrom = (array, key, i) => {
    array[key].splice(i, 1);
}

export const hasPermission = (requiredPermissions) => {
    if (getLoginUser() && getLoginUser().is_owner) return true;
    const permissions = store.getters && store.getters['auth/Permissions'];
    if (permissions) {
        return permissions.some(permission => {
            return requiredPermissions.includes(permission);
        });
    }
}

export const canAccess = (to) => {
    if (getLoginUser() && getLoginUser().is_owner) return true;
    const guard = to.meta.permissions
    if (guard && guard instanceof Array && guard.length > 0) {
        if (guard.includes('*')) return true;
        const hasAccess = hasPermission(guard)

        if (!hasAccess) return false
    }

    return true
}

export const routesWithChild = () => {
    return router
        .getRoutes()
        .filter((route) => route.children.length)
}

export const routeChildren = () => {
    if (router.getRoutes().length) {
        return routesWithChild()
            .reduce((a, r) => [...a, ...r.children], []);
    }
}

export const toCapitalize = (string) => {
    if (string) return string[0].toUpperCase() + string.slice(1);
}

export const userNameCapitalize = () => {
    return getLoginUser() && toCapitalize(getLoginUser().username)
}

export const userAvatar = () => {
    return getLoginUser() && getLoginUser().avatar.url
}

export const loginUserId = () => {
    return getLoginUser() && getLoginUser().id
}

export const floatify = (number) => {
    return parseFloat((number).toFixed(10));
}

export const addOptiondTo = (moduleName, formData) => {
    const getOptions = computed(() => store.getters[`${moduleName}/getOptions`]);
    const findOption = getOptions.value.find(
        (opt) => opt.id === formData[`${moduleName}_id`]
    );

    if (!findOption) {
        getOptions.value.unshift(formData[`${moduleName}`]);
    }
}
