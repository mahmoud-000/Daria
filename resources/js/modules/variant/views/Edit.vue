<script setup>
import { defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { addOptiondTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("variant/fetchVariant", route.params.id);
const formData = computed(() => store.getters["variant/getVariant"]);

const VARIABLE = 2;
await store.dispatch("item/fetchOptions", { type: VARIABLE });

onMounted(async () => {
    if (formData.value.item_id) {
        addOptiondTo("item", formData.value);
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
