<script setup>
import { colors, useQuasar, setCssVar } from "quasar";
import { ref, watch } from "vue";
import themes from "../../utils/themes";
import { useStore } from "vuex";

const $q = useQuasar();
const store = useStore();

const { getPaletteColor } = colors;
const getTheme = computed(() => store.getters["getTheme"], { root: true });
const theme = ref("");

watch(
    theme,
    (new_theme) => {
        let newTheme = themes.find((theme) => theme.name === new_theme);
        if (!newTheme) {
            newTheme = themes.find((theme) => theme.name === "ui-ux-1-dark");
        }

        setCssVar("primary", newTheme.colors.primary);
        setCssVar("secondary", newTheme.colors.secondary);
        setCssVar("accent", newTheme.colors.accent);
        setCssVar("info", newTheme.colors.info);
        setCssVar("warning", newTheme.colors.warning);
        setCssVar("positive", newTheme.colors.positive);
        setCssVar("negative", newTheme.colors.negative);

        if (newTheme.isDark) {
            setCssVar("dark", getPaletteColor(newTheme.colors["dark"]));
            setCssVar(
                "dark-page",
                getPaletteColor(newTheme.colors["dark-page"])
            );
        }
        store.commit("SET_THEME", new_theme, { root: true });
        
        $q.cookies.set("theme", new_theme, {
            path: "/",
        });
        $q.dark.isActive = newTheme.isDark;
        $q.dark.set(newTheme.isDark);
    },
    { immediate: true }
);
</script>

<template>
    <q-select
        v-model="theme"
        :options="themes"
        option-label="name"
        option-value="name"
        emit-value
        transition-show="jump-up"
        transition-hide="jump-up"
        behavior="menu"
        color="primary"
    />
</template>
