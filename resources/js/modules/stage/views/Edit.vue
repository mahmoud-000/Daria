<script setup>
import { defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { addOptiondTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("stage/fetchStage", route.params.id);
const formData = computed(() => store.getters["stage/getStage"]);
await store.dispatch("pipeline/fetchOptions");

onMounted(async () => {
    if (formData.value.pipeline_id) {
        addOptiondTo("pipeline", formData.value);
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
