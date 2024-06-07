<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    name: "",
    app_name: null,
    is_active: 0,
    stages: [
        {
            name: 'New',
            color: '#ffffff',
            complete: 0,
            default: true
        },
        {
            name: 'Complete',
            color: '#ffffff',
            complete: 100,
            default: true
        },
    ],
    remarks: "",
});
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
