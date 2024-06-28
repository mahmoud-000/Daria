<script setup>
import { ref, defineAsyncComponent, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
import { useInvoiceDetail } from "../../../composables/invoiceDetail";
import { addOptionTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
const route = useRoute();

await store.dispatch("saleReturn/fetchFormOptions", { app_name: "saleReturn" });
await store.dispatch("saleReturn/fetchSaleReturn", route.params.id);

const formData = computed(() => store.getters["saleReturn/getSaleReturn"]);

const { detailCalculate } = useInvoiceDetail("cost");

onMounted(() => {
    formData.value.deletedDetails = ref([])
    formData.value.deletedPayments = ref([])
   
    if (formData.value.customer_id) {
        addOptionTo("customer", formData.value);
    }

    if (formData.value.delegate_id) {
        addOptionTo("delegate", formData.value);
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
