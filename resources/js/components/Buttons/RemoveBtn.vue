<script setup>
import { defineAsyncComponent } from "vue";
import { useI18n } from "vue-i18n";

const BaseBtn = defineAsyncComponent(() => import("../Buttons/BaseBtn.vue"));
const { t } = useI18n();
</script>
<template>
    <BaseBtn
        glossy
        round
        icon="remove"
        color="red-10"
        :toolbar="t('action.delete')"
        v-bind="$attrs"
    />
</template>
