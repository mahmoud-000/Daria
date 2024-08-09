<script setup>
import { defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { addOptionTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("department/fetchDepartment", route.params.id);
const formData = computed(() => store.getters["department/getDepartment"]);
await store.dispatch("department/fetchOptions", { form_id: formData.value.id });
await store.dispatch("user/fetchOptions");

onMounted(async () => {
    if (formData.value.department_id) {
        addOptionTo('department', formData.value, 'parent')
    }

    if (formData.value.manager_id) {
        addOptionTo('user', formData.value, 'manager')
    }
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
