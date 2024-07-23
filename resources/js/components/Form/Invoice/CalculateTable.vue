<script setup>
import { toRefs, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useRoute } from "vue-router";
import { useInvoice } from "../../../composables/invoiceCalculate";
import { numberFormatWithCurrency } from "../../../utils/helpers";

const props = defineProps({
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
    hideRows: {
        type: Array,
        required: false,
        default: () => [],
    },
    costOrPrice: {
        type: String,
        required: true,
        default: "cost",
    },
});
const { formData, costOrPrice, hideRows } = toRefs(props);

const { t } = useI18n();
const route = useRoute();

const showInTable = (row) => {
    return !hideRows.value?.includes(row);
};

const { invoiceCalculation } = useInvoice(formData.value, costOrPrice.value);

// Discount - Tax - Shipping - Other Expenses - Details Watch
watch(
    [
        () => formData.value.tax,
        () => formData.value.discount,
        () => formData.value.discount_type,
        () => formData.value.delegate_id,
        () => formData.value.shipping,
        () => formData.value.other_expenses,
        () => formData.value.details,
    ],
    (val) => {
        invoiceCalculation();
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <q-markup-table dense class="calculate col-lg-4 col-md-6 col-sm-12">
        <tbody>
            <tr v-if="showInTable('tax')">
                <td class="bold">
                    {{ $t("table.order_tax") }}
                </td>
                <td>
                    <span>
                        {{ numberFormatWithCurrency(formData.tax_net) }}

                        (
                        {{ $filters.formatNumber(formData.tax, 2) }}
                        %)</span
                    >
                </td>
            </tr>
            <tr v-if="showInTable('discount')">
                <td class="bold">
                    {{ $t("table.discount") }}
                </td>
                <td>
                    <span>
                        {{ numberFormatWithCurrency(formData.discount_net) }}
                    </span>
                    <span v-if="formData.discount_type === 2">
                        (
                        {{ $filters.formatNumber(formData.discount, 2) }}
                        %)
                    </span>
                </td>
            </tr>
            <tr v-if="showInTable('shipping')">
                <td class="bold">
                    {{ $t("table.shipping") }}
                </td>
                <td>
                    <span>
                        {{ numberFormatWithCurrency(formData.shipping_net) }}
                    </span>
                    <span v-if="formData.commission_type === 2">
                        (
                        {{ $filters.formatNumber(formData.shipping, 2) }}
                        %)
                    </span>
                </td>
            </tr>
            <tr v-if="showInTable('shipping')">
                <td class="bold">
                    {{ $t("table.other_expenses") }}
                </td>
                <td>
                    {{ numberFormatWithCurrency(formData.other_expenses) }}
                </td>
            </tr>
            <tr v-if="showInTable('grand_total')">
                <td>
                    <span class="font-weight-bold">{{
                        $t("table.grand_total")
                    }}</span>
                </td>
                <td>
                    <span class="font-weight-bold">
                        {{ numberFormatWithCurrency(formData.grand_total) }}
                    </span>
                </td>
            </tr>
            <tr v-if="showInTable('paid_amount')">
                <td>
                    <span class="font-weight-bold">{{
                        $t("table.paid_amount")
                    }}</span>
                </td>
                <td>
                    <span class="font-weight-bold">
                        {{ numberFormatWithCurrency(formData.paid_amount) }}
                    </span>
                </td>
            </tr>
            <tr v-if="showInTable('due')">
                <td>
                    <span class="font-weight-bold">{{ $t("table.due") }}</span>
                </td>
                <td>
                    <span class="font-weight-bold">
                        {{ numberFormatWithCurrency(formData.due) }}
                    </span>
                </td>
            </tr>
        </tbody>
    </q-markup-table>
</template>

<style scoped>
.calculate.q-data-table {
    line-height: 2.5 !important;
}
.calculate tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
}
</style>
