<script setup>
import { defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { addOptionTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await Promise.all([
    store.dispatch("category/fetchCategory", route.params.id),
    store.dispatch("category/fetchOptions", { form_id: route.params.id }),
]);

const formData = computed(() => store.getters["category/getCategory"]);

onMounted(async () => {
    if (formData.value.category_id) {
        addOptionTo("category", formData.value);
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
