<script setup>
import { AppFullscreen } from "quasar";
import { useI18n } from "vue-i18n";
import { BaseBtn } from "../../components/import";
const { t } = useI18n();
</script>

<template>
    <BaseBtn
        dark
        glossy
        dense
        round
        unelevated
        color="primary"
        @click="AppFullscreen.toggle()"
        :icon="AppFullscreen.isActive ? 'fullscreen_exit' : 'fullscreen'"
        :toolbar="t('fullscreen')"
    />
</template>
