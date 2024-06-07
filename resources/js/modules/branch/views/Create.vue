<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { branch } from "../../../utils/constraints";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    ...branch,
    company_id: null,
    remarks: "",
});

const store = useStore();
await store.dispatch("company/fetchOptions");
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
