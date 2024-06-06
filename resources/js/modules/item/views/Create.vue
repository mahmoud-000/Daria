<script setup>
import { defineAsyncComponent, reactive } from "vue";
import { TheSpinner } from "../../../components/import";
const Form = defineAsyncComponent(() => import("../components/Form.vue"));

const formData = reactive({
    name: '',
    label: '',
    item_desc: '',
    cost: 0,
    price: 0,
    code: '',
    barcode_type: 1,
    tax: 0,
    tax_type: 1,
    stock_alert: 0,
    currency: null,
    category_id: null,
    brand_id: null,
    unit_id: null,
    sale_unit_id: null,
    purchase_unit_id: null,
    is_active: 0,
    is_available_for_purchase: 0,
    is_available_for_sale: 0,
    is_available_for_edit_in_purchase: 0,
    is_available_for_edit_in_sale: 0,
    type: 1,
    product_type: 1,
    remarks: "",
    variants: []
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
