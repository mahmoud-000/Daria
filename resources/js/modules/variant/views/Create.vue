<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { variant } from "../../../utils/constraints";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    ...variant,
    item_id: null,
    remarks: "",
});
const store = useStore();
const VARIABLE = 2;
await store.dispatch("item/fetchOptions", { type: VARIABLE });
</script>

<template>
    <Suspense>
        <template #default>
            <Form :form-data="formData" />
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>
