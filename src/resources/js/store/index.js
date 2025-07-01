import { Cookies, Dark, setCssVar } from "quasar";
import { createLogger, createStore } from "vuex";

import themes from "../utils/themes";
import router from "../router";

// Create a new store instance.
const store = createStore({
    // plugins: [createLogger()],
    state: {
        drawerMini: false,
        errors: [],
        message: null,
        loading: true,
        theme: Cookies.get("theme") ?? "Purple",
        mode: Cookies.get("mode") ?? "dark",
        lighterThemes: themes.filter((t) => !t.isDark),
        textColorWhite: "white",
        textColorDark: "dark",
        activeClassForBg: "bg-primary",
    },
    mutations: {
        SET_DRAWER_MINI(state, mini) {
            state.drawerMini = mini
        },
        SET_ERROR(state, error) {
            state.errors = [];
            Array.isArray(error)
                ? state.errors.push(...error)
                : state.errors.push(error);
        },
        ADD_MESSAGE(state, message) {
            state.message = message;
        },
        SET_LOADING(state, loading) {
            state.loading = loading;
        },
        SET_THEME(state, theme) {
            if (theme) {
                state.theme = theme;
            }
            
            Cookies.set("theme", state.theme, {
                path: "/",
            });

            let { colors } = themes.find((theme) => theme.name === state.theme);

            setCssVar("primary", colors.primary);
            setCssVar("secondary", colors.secondary);
            setCssVar("accent", colors.accent);
            setCssVar("info", colors.info);
            setCssVar("warning", colors.warning);
            setCssVar("positive", colors.positive);
            setCssVar("negative", colors.negative);
            setCssVar("dark", colors.dark);
            setCssVar("dark-page", colors.darkPage);
        },
        SET_MODE(state, mode) {
            if (mode) {
                state.mode = mode;
            }

            Cookies.set("mode", state.mode, {
                path: "/",
            });
            Dark.isActive = state.mode === "dark";
            Dark.set(state.mode === "dark");
        },
    },
    getters: {
        getDrawerMini: (state) => state.drawerMini,
        getErrors: (state) => state.errors[0],
        getLoading: (state) => state.loading,
        getTheme: (state) => state.theme,
        getMode: (state) => state.mode,
        colorBasedOnMode: ({ textColorWhite, textColorDark }, { getMode }) => getMode === 'dark' ? textColorWhite : textColorDark,
        isLighterTheme: ({ lighterThemes }, { getTheme }) =>
            lighterThemes.some((light) => light.name === getTheme),
        getTextColor: (
            { textColorWhite, textColorDark },
            { isLighterTheme }
        ) => {
            return isLighterTheme ? textColorDark : textColorWhite;
        },
        getTextClass: (
            { textColorWhite, textColorDark },
            { isLighterTheme }
        ) => {
            return isLighterTheme ? `text-${textColorDark}` : `text-${textColorWhite}`;
        },
        isActiveClass:
            (
                { activeClassForBg },
                { getTheme, getTextClass }
            ) =>
                (activeLink) => {
                    let array = []
                    let moduleName = activeLink.split('.')[0];

                    // Group Settings
                    if (['role', 'brand', 'category', 'warehouse', 'unit', 'pipeline', 'stage'].includes(moduleName)) {
                        array['setting'] = `${activeClassForBg} ${getTextClass}`;
                        return array;
                    }

                    // Group People
                    if (['user', 'customer', 'supplier', 'delegate'].includes(moduleName)) {
                        array['people'] = `${activeClassForBg} ${getTextClass}`;
                        return array;
                    }

                    // Group Invoices
                    if (['purchase', 'purchaseReturn', 'sale', 'saleReturn', 'quotation', 'adjustment'].includes(moduleName)) {
                        array['invoice'] = `${activeClassForBg} ${getTextClass}`;
                        return array;
                    }

                    // Group Organization
                    if (['company', 'branch', 'department'].includes(moduleName)) {
                        array['organization'] = `${activeClassForBg} ${getTextClass}`;
                        return array;
                    }

                    router.currentRoute.value.matched.some(({ name }) => name === activeLink)
                        ? array[moduleName] = `${activeClassForBg} ${getTextClass}`
                        : "";

                    return array;
                },
    },
    modules: {},
});

export default store;
