<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner, BaseBtn } from "../../../components/import";
import { numberFormatWithCurrency } from "../../../utils/helpers";
import { useI18n } from "vue-i18n";

const config = reactive({
    moduleName: "quotation",
    fetchItems: "fetchQuotations",
    getItems: "getQuotations",
    destroyItem: "destroyQuotation",
    bulkDestroyItems: "bulkDestroyQuotations",
    options: {
        export: true,
    }, // import csv - export csv
});

const { t } = useI18n();
const toSale = (qoutation) => {};
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <!-- Grand Total -->
                <template #body-cell-grand_total="props">
                    <q-td class="text-center" :props="props">
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>
                </template>

                <template #moreActions>
                    <BaseBtn
                        glossy
                        round
                        icon="add_shopping_cart"
                        color="secondary"
                        class="q-ml-sm"
                        :toolbar="t('table.create_a_sale')"
                        @click="toSale(props.value)"
                    />
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
