<script setup>
import { computed, defineAsyncComponent, onMounted, reactive } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { TheSpinner } from "../../../components/import";
import { useInvoiceDetail } from "../../../composables/invoiceDetail";
import { addOptionTo } from "../../../utils/helpers";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
await store.dispatch("sale/fetchFormOptions", { app_name: "sale" });

const router = useRouter();

const lastRoute = computed(() => {
  const backUrl = router.options.history.state.back
  const route = router.resolve({ path: `${backUrl}` })

  return route.name
})

const formDataSale = reactive({
    date: new Date().toISOString().slice(0, 10),
    user_id: null,
    customer_id: null,
    warehouse_id: null,
    doc_invoice_number: null,
    discount_type: 1,
    discount: 0,
    tax: 0,
    paid_amount: 0,
    pipeline_id: null,
    stage_id: null,
    payment_status: 2,
    grand_total: 0,
    commission_type: 1,
    shipping: 0,
    other_expenses: 0,
    payments: [],
    details: [],
    remarks: "",
    deletedDetails: [],
    deletedPayments: [],
});

const formData = computed(() => {
    const quotation = computed(() => store.getters["quotation/getQuotation"]);
    const { detailCalculate } = useInvoiceDetail("price");

    if (lastRoute.value === 'quotation.list' && Object.keys(quotation.value).length !== 0) {
        if (quotation.value.customer_id) {
            addOptionTo("customer", quotation.value);
        }

        if (quotation.value.delegate_id) {
            addOptionTo("delegate", quotation.value);
        }

        quotation.value?.details.forEach((detail) => {
            detailCalculate(detail);
        });
        return quotation.value;
    }

    return formDataSale;
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
