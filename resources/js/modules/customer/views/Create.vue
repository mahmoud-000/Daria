<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    fullname: "",
    company_name: "",
    email: "",
    is_active: 0,
    type: 1,
    contacts: [],
    locations: [],
    remarks: ""
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
