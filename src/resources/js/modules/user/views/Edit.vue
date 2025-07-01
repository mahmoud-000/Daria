<script setup>
import { defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { addOptionsTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await Promise.all([
    store.dispatch("user/fetchUser", route.params.id),
    store.dispatch("permission/fetchOptions"),
    store.dispatch("role/fetchOptions"),
]);

const formData = computed(() => store.getters["user/getUser"]);

onMounted(async () => {
    if (formData.value.role_ids) {
        addOptionsTo("role", "roles", "id", formData.value);
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
