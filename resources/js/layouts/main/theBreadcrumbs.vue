<script setup>
import { ref, watch } from "vue";
import { Dark } from "quasar";
import { useRoute } from "vue-router";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const route = useRoute();
const breadcrumbs = ref([]);
const moduleName = ref("");

watch(
    () => route.meta.breadcrumbs,
    (bread) => {
        breadcrumbs.value = bread;
    },
    { immediate: true }
);

watch(
    () => route.name,
    (name) => {
        moduleName.value = name.split('.')[0];
    },
    { immediate: true }
);
</script>

<template>
    <q-card
        v-if="!['404', '403'].includes(route.name)"
        :class="{
            'bg-white text-black': !Dark.isActive,
        }"
        class="q-mb-md"
        flat
    >
        <q-toolbar>
            <q-btn flat round dense icon="assignment_ind" />

            <q-toolbar-title>
                {{
                    t(`links.manage`, { module: t(`links.${moduleName}`, 2) })
                }}</q-toolbar-title
            >

            <q-btn flat round dense icon="sim_card" class="q-mr-xs" />
            <q-btn flat round dense icon="gamepad" />
        </q-toolbar>
        <q-toolbar inset>
            <q-breadcrumbs active-color="secondary" style="font-size: 16px">
                <q-breadcrumbs-el
                    v-for="(bread, i) in breadcrumbs"
                    :key="i"
                    :label="t(`${bread.label}`, 2)"
                    :icon="bread?.icon"
                    :to="{ name: `${bread?.to}` }"
                />
            </q-breadcrumbs>
        </q-toolbar>
    </q-card>
</template>
