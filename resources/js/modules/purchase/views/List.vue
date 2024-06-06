<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";
import { numberFormatWithCurrency } from "../../../utils/helpers";

const config = reactive({
    moduleName: "purchase",
    fetchItems: "fetchPurchases",
    getItems: "getPurchases",
    destroyItem: "destroyPurchase",
    bulkDestroyItems: "bulkDestroyPurchases",
    options: {
        export: true,
    }, // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <!-- Paid Amount -->
                <template #body-cell-paid_amount="props">
                    <q-td :props="props">
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>
                </template>

                <!-- Grand Total -->
                <template #body-cell-grand_total="props">
                    <q-td :props="props">
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>
                </template>

                <!-- Due -->
                <template #body-cell-due="props">
                    <q-td :props="props">
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>
                </template>

                <!-- Payment Status -->
                <template #body-cell-payment_status="props">
                    <q-td :props="props">
                        <q-badge
                            outline
                            v-if="props.row.payment_status === 1"
                            color="positive"
                            >{{ props.value }}</q-badge
                        >
                        <q-badge
                            outline
                            v-if="props.row.payment_status === 2"
                            color="negative"
                            >{{ props.value }}</q-badge
                        >
                        <q-badge
                            outline
                            v-if="props.row.payment_status === 3"
                            color="primary"
                            >{{ props.value }}</q-badge
                        >
                    </q-td>
                </template>
            </BaseTable>
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>
