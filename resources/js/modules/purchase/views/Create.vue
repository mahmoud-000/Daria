<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { useStore } from "vuex";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const store = useStore();
await store.dispatch("purchase/fetchFormOptions");


const formData = reactive({
    date: new Date().toISOString().slice(0, 10),
    user_id: null,
    supplier_id: null,
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
