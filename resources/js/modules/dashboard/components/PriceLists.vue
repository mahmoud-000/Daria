<script setup>
import { reactive } from "vue";
import { columnsPriceLists } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const configPriceLists = reactive({
    moduleName: "priceList",
    fetchItems: "fetchPriceLists",
    getItems: "getPriceLists",
    destroyItem: "destroyPriceList",
    bulkDestroyItems: "bulkDestroyPriceLists",
    options: {
        import: false,
        export: true,
    }, // import csv - export csv
});
</script>

<template>
    <div class="row col-lg-6 col-sm-12 q-mb-md">
        <Suspense>
            <template #default>
                <BaseTable
                    :config="configPriceLists"
                    :columns="columnsPriceLists"
                />
            </template>
            <template #fallback>
                <div class="fixed-center">
                    <the-spinner />
                </div>
            </template>
        </Suspense>
    </div>
</template>
