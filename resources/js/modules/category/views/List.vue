<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const config = reactive({
    moduleName: "category",
    fetchItems: "fetchCategories",
    getItems: "getCategories",
    destroyItem: "destroyCategory",
    bulkDestroyItems: "bulkDestroyCategories",
    options: {
        export: true,
    }, // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <template #body-cell-logo="props">
                    <q-td :props="props">
                        <q-avatar size="md">
                            <q-img :src="props.value" />
                        </q-avatar>
                    </q-td>
                </template>
                <template #body-cell-parent="props">
                    <q-td>
                        <q-list dense dark>
                            <q-item>
                                <q-item-section>
                                    <q-item-label>{{
                                        props.value
                                    }}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-item-label caption>
                                        <q-avatar size="sm">
                                            <q-img :src="props.row.category?.logo.url" />
                                        </q-avatar>
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
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
