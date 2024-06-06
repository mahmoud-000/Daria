<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    name: "",
    category_id: null,
    is_active: 0,
    remarks: "",
});
const store = useStore();
await store.dispatch("category/fetchOptions");
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
