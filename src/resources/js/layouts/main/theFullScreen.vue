<script setup>
import { AppFullscreen } from "quasar";
import { useI18n } from "vue-i18n";
import { BaseBtn } from "../../components/import";
const { t } = useI18n();
</script>

<template>
    <BaseBtn
        dark
        dense
        flat
        round
        unelevated
        :color="$q.dark.isActive ? 'white' : 'dark'"
        @click="AppFullscreen.toggle()"
        :icon="
            AppFullscreen.isActive
                ? 'fa-solid fa-compress'
                : 'fa-solid fa-expand'
        "
        :toolbar="t('fullscreen')"
    />
</template>
