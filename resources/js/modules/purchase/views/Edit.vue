<script setup>
import { ref, defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { useInvoiceDetail } from "../../../composables/invoiceDetail";
import { addOptiondTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("purchase/fetchFormOptions", { app_name: "purchase" });
await store.dispatch("purchase/fetchPurchase", route.params.id);

const formData = computed(() => store.getters["purchase/getPurchase"]);

const { detailCalculate } = useInvoiceDetail("cost");

onMounted(() => {
    formData.value.deletedDetails = ref([])
    formData.value.deletedPayments = ref([])
   
    if (formData.value.supplier_id) {
        addOptiondTo("supplier", formData.value);
    }

    if (formData.value.delegate_id) {
        addOptiondTo("delegate", formData.value);
    }

    formData.value?.details.forEach((detail) => {
        detailCalculate(detail);
    });
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
