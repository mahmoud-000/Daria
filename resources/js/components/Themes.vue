<script setup>
import { Cookies } from "quasar";
import { ref, computed, watch } from "vue";

import themes from "../utils/themes";
import { useStore } from "vuex";

const themeToggle = ref(Cookies.get("theme"));
const modeToggle = ref(Cookies.get("mode"));
const THEME_NAME = ref("");

const { commit, getters } = useStore();
const getTheme = computed(() => getters["getTheme"], { root: true });

watch(
    themeToggle,
    (new_theme) => {
        let newTheme = themes.find((theme) => theme.name === new_theme);

        if (!newTheme || !new_theme) {
            newTheme = themes.find((theme) => theme.name === getTheme.value);
        }

        THEME_NAME.value = newTheme.name;

        commit("SET_THEME", new_theme, { root: true });
    },
    { immediate: true }
);

watch(
    modeToggle,
    (new_mode) => {
        commit("SET_MODE", new_mode, { root: true });
    },
    { immediate: true }
);
</script>
<template>
    <q-card
        class="q-pa-xl col-22 col-lg-3 col-md-6 col-sm-6 text-center"
        flat
        style="
            max-width: 70rem;
            margin-left: auto;
            margin-right: auto;
            border-radius: 20px;
        "
    >
        <h4 class="text-faded">
            <strong>
                {{ $t("links.choose_your_theme") }}
            </strong>
        </h4>
        <h5 class="text-faded">
            Theme: <strong>{{ THEME_NAME }}</strong>
            <q-toggle
                keep-color
                label="Dark"
                v-model="modeToggle"
                color="primary"
                true-value="dark"
                false-value="light"
                checked-icon="check"
                unchecked-icon="clear"
            />
        </h5>

        <q-card v-for="(theme, i) in themes">
            <q-card-section class="text-h3">
                {{ theme.name }}
                <q-toggle
                    keep-color
                    v-model="themeToggle"
                    color="primary"
                    :true-value="theme.name"
                    :false-value="themeToggle"
                    checked-icon="check"
                    unchecked-icon="clear"
                />
            </q-card-section>

            <q-card-section class="row q-gutter-md text-center flex-center">
                <p
                    class="q-pa-sm col-2"
                    :style="`background-color: ${theme.colors.primary}`"
                >
                    Primary
                </p>

                <p
                    class="text-white q-pa-md col-6 col-lg-2 col-md-3 col-sm-6"
                    :style="`background-color: ${theme.colors.secondary}`"
                >
                    Secondary
                </p>

                <p
                    class="text-white q-pa-md col-6 col-lg-2 col-md-3 col-sm-6"
                    :style="`background-color: ${theme.colors.accent}`"
                >
                    Accent
                </p>

                <p
                    class="text-white q-pa-md col-6 col-lg-2 col-md-3 col-sm-6"
                    :style="`background-color: ${theme.colors.info}`"
                >
                    Info
                </p>

                <p
                    class="text-white q-pa-md col-6 col-lg-2 col-md-3 col-sm-6"
                    :style="`background-color: ${theme.colors.warning}`"
                >
                    Warning
                </p>

                <p
                    class="text-white q-pa-md col-6 col-lg-2 col-md-3 col-sm-6"
                    :style="`background-color: ${theme.colors.positive}`"
                >
                    Positive
                </p>

                <p
                    class="text-white q-pa-md col-6 col-lg-2 col-md-3 col-sm-6"
                    :style="`background-color: ${theme.colors.negative}`"
                >
                    Negative
                </p>
            </q-card-section>
        </q-card>
    </q-card>
</template>
