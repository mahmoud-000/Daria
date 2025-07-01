<script setup>
import { defineAsyncComponent, ref, toRefs } from "vue";
import {
    VueCsvToggleHeaders,
    VueCsvSubmit,
    VueCsvMap,
    VueCsvInput,
    VueCsvErrors,
    VueCsvImport,
} from "vue-csv-import";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
const BaseBtn = defineAsyncComponent(() => import("../Buttons/BaseBtn.vue"));
const props = defineProps({
    dialogImporter: {
        type: Boolean,
        default: () => true,
    },
    fields: {
        type: Object,
        required: true,
        default: () => {},
    },
    config: {
        type: Object,
        required: true,
        default: () => {},
    },
});
const { fields, config, dialogImporter } = toRefs(props);
const mappedCsv = ref(null);
const router = useRouter();

// const fieldsWithoutActions = Object.keys(fields.value)
//   .filter((key) => key !== "actions")
//   .reduce((cur, key) => {
//     return Object.assign(cur, { [key]: fields.value[key] });
//   }, {});
const store = useStore();
const submit = async () => {
    await store.dispatch(
        `${config.value.moduleName}/${config.value.importCsv}`,
        mappedCsv.value
    );
    mappedCsv.value = null;
    router.push({ name: `${config.value.moduleName}.list` });
};
</script>
<template>
    <q-dialog
        :modelValue="dialogImporter"
        @update:modelValue="(value) => (dialogImporter = value)"
        transition-show="slide-up"
        transition-hide="slide-down"
        position="top"
        class="import_dialog"
    >
        <q-card class="text-center" style="width: 700px; max-width: 80vw">
            <q-bar>
                <q-space />
                <q-btn dense flat icon="close" v-close-popup>
                    <q-tooltip class="bg-black text-white">{{
                        $t("action.close")
                    }}</q-tooltip>
                </q-btn>
            </q-bar>
            <q-card-section>
                <!-- <slot /> -->
                <vue-csv-import v-model="mappedCsv" :fields="fields">
                    <!-- <vue-csv-toggle-headers></vue-csv-toggle-headers> -->
                    <!-- <vue-csv-toggle-headers v-slot="{ hasHeaders, toggle }">
            <q-btn color="warning" size="sm" @click.prevent="toggle">Has Headers</q-btn>
          </vue-csv-toggle-headers> -->
                    <vue-csv-errors></vue-csv-errors>
                    <label>
                        <vue-csv-input
                            class="bg-black q-pa-lg w-full"
                        ></vue-csv-input>
                        <span class="clickable bg-black text-white q-pa-md">{{
                            $t("action.choose_your_file")
                        }}</span>
                    </label>
                    <vue-csv-map></vue-csv-map>
                </vue-csv-import>
                <div class="q-pa-sm">
                    <q-table
                        flat
                        bordered
                        separator="cell"
                        dense
                        v-if="mappedCsv"
                        :rows="mappedCsv.slice(1)"
                        row-key="name"
                    />
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn class="bg-primary text-white" @click="submit">{{
                    $t("action.save")
                }}</q-btn>
                <slot name="action" />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<style>
.import_dialog input[type="file"] {
    width: 0;
    height: 0;
    overflow: hidden;
    opacity: 0;
}
.import_dialog table {
    width: 100%;
    margin-top: 20px;
}
.import_dialog select {
    width: 100%;
    padding: 10px;
}
</style>
