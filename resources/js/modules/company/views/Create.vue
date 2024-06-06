<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    name: "",
    is_active: 0,
    branches: [
        {
            name: "",
            email: "",
            address: "",
            country: null,
            city: null,
            zip: "",
            state: "",
            phone: "",
            mobile: "",
            is_active: 1,
            is_main: 1
        }
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
