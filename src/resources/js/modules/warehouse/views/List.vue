<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const config = reactive({
    moduleName: "warehouse",
    fetchItems: "fetchWarehouses",
    getItems: "getWarehouses",
    destroyItem: "destroyWarehouse",
    bulkDestroyItems: "bulkDestroyWarehouses",
    options: {
        export: true
    } // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns"/>
        </template>
        <template #fallback>
            <div class="fixed-center">
                <the-spinner />
            </div>
        </template>
    </Suspense>
</template>
