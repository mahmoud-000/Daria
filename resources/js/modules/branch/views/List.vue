<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const config = reactive({
    moduleName: "branch",
    fetchItems: "fetchBranches",
    getItems: "getBranches",
    destroyItem: "destroyBranch",
    bulkDestroyItems: "bulkDestroyBranches",
    options: {
        export: true,
    }, // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <template #body-cell-company="props">
                    <q-td class="text-center" :props="props">
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
                                            <q-img
                                                :src="
                                                    props.row.company?.logo.url
                                                "
                                            />
                                        </q-avatar>
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-td>
                </template>
                <!-- Is Main Badge -->
                <template #body-cell-is_main="props">
                    <q-td class="text-center" :props="props">
                        <q-badge
                            :color="
                                props.row.is_main
                                    ? 'positive'
                                    : 'negative'
                            "
                            class="q-ma-none"
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
