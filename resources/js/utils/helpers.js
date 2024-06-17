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

export const addOptionTo = (moduleName, formData) => {
    const getOptions = computed(() => store.getters[`${moduleName}/getOptions`]);
    const findOption = getOptions.value.find(
        (opt) => opt.id === formData[`${moduleName}_id`]
    );

    if (!findOption) {
        getOptions.value.unshift(formData[`${moduleName}`]);
    }
}

export const addOptionsTo = (moduleName, arrayKey, pluck_key, formData) => {
    const getOptions = computed(() => store.getters[`${moduleName}/getOptions`]);
    const pluckKey = pluck(getOptions.value, pluck_key);

    const deletedValues = difference(formData[`${moduleName}_ids`], pluckKey)

    if (deletedValues.length) {
        const deletedObjects = formData[`${arrayKey}`].filter((obj) =>
            deletedValues.includes(obj.id)
        );

        if(moduleName === 'role') {
            const result = deletedObjects.map(role => ({ id: role.id, name: role.name, permissions: pluck(role.permissions, 'name') }));
            getOptions.value.unshift(...result);
            return;
        }

        getOptions.value.unshift(...deletedObjects);

    }
}

export const pluck = (arr, key) => arr.map(i => i[key]);

// pluckKeys(simpsons, ['name', 'age']);
const pluckKeys = (arr, keys) => arr.map(i => keys.map(k => i[k]));


// pluckOnly(simpsons, 'age');
// [8, 36, 34, 10]

// pluckOnly(simpsons, 'name', 'age');
// [['Lisa', 8], ['Homer', 36], ['Marge', 34], ['Bart', 10]]
const pluckOnly = (arr, ...keys) =>
    keys.length > 1 ?
        arr.map(i => keys.map(k => i[k])) :
        arr.map(i => i[keys[0]]);


export const intersection = (arr1, arr2) => arr1.filter(x => arr2.includes(x));

export const difference = (arr1, arr2) => arr1.filter(x => !arr2.includes(x));

export const symDifference = (arr1, arr2) => arr1.filter(x => !arr2.includes(x))
    .concat(arr2.filter(x => !arr1.includes(x)));