<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";

const config = reactive({
    moduleName: "stage",
    fetchItems: "fetchStages",
    getItems: "getStages",
    destroyItem: "destroyStage",
    bulkDestroyItems: "bulkDestroyStages",
    options: {
        export: true,
    }, // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <template #body-cell-pipeline="props">
                    <q-td class="text-center" :props="props">
                        {{ props.value }}
                    </q-td>
                </template>
                <!-- Is Default Badge -->
                <template #body-cell-is_default="props">
                    <q-td class="text-center" :props="props">
                        <q-badge
                            :color="
                                props.row.is_default ? 'positive' : 'negative'
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
