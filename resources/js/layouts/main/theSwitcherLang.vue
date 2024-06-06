<script setup>
import languages from "quasar/lang/index.json";

import { watch, computed, defineAsyncComponent } from "vue";
import { Quasar } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { langList } from "../../utils/boot_lang";

const CountryFlag = defineAsyncComponent(() => import("vue-country-flag-next"));

const store = useStore();
const langs = computed(() => store.getters["locale/getLocales"]);
const appLanguages = languages.filter((lang) =>
    langs.value.includes(lang.isoName === "en-US" ? "en" : lang.isoName)
);

const localeOptions = appLanguages.map((lang) => ({
    label: lang.nativeName,
    value: lang.isoName === "en-US" ? "en" : lang.isoName,
    flag: lang.isoName === "en-US" ? "usa" : "sa",
}));

const { locale } = useI18n({ useScope: "global" });

watch(locale, (val) => {
    const langIso = val === "en" ? "en-US" : val;
    // dynamic import, so loading on demand only
    try {
        langList[`../../../node_modules/quasar/lang/${langIso}.mjs`]().then(
            (lang) => {
                Quasar.lang.set(lang.default);
                store.dispatch("locale/setLocale", val);
            }
        );
    } catch (err) {
        console.log(err);
        // Requested Quasar Language Pack does not exist,
        // let's not break the app, so catching error
    }
});

const getTextColor = computed(() => store.getters["getTextColor"]);

const onLocaleClick = async (val) => {
    if (val !== locale.value) {
        locale.value = val;
    }
};
</script>

<template>
    <q-btn-dropdown push glossy dense no-caps flat icon="language">
        <q-list>
            <q-item
                v-for="(locale, i) in localeOptions"
                :key="i"
                clickable
                v-close-popup
                dense
                @click="onLocaleClick(locale.value)"
            >
                <q-item-section avatar>
                    <CountryFlag :country="locale.flag" size="normal" />
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ locale.label }}</q-item-label>
                </q-item-section>
            </q-item>
        </q-list>
    </q-btn-dropdown>
    <!-- <q-select
        dense
        outlined
        rounded
        options-cover
        options-dense
        emit-value
        transition-show="jump-up"
        transition-hide="jump-up"
        behavior="menu"
        map-options
        v-model="locale"
        :options="localeOptions"
        :label="$t('global.switch_language')"
        borderless
        style="min-width: 120px"
    >
        <template #selected-item="scope">
            <q-chip
                :tabindex="scope.tabindex"
                color="primary"
                :text-color="getTextColor"
                class="q-ma-none"
            >
                {{ scope.opt.label }}
            </q-chip>
        </template>
    </q-select> -->
</template>
