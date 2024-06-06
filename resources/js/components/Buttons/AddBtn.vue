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
        icon="add"
        color="positive"
        :toolbar="t('action.add')"
        v-bind="$attrs"
    />
</template>
