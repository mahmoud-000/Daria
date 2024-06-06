<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";
const config = reactive({
    moduleName: "customer",
    fetchItems: "fetchCustomers",
    getItems: "getCustomers",
    destroyItem: "destroyCustomer",
    bulkDestroyItems: "bulkDestroyCustomers",
    options: {
        import: false,
        export: true,
    }, // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <!-- Avatar -->
                <template #body-cell-avatar="props">
                    <q-td :props="props">
                        <q-avatar size="md">
                            <q-img :src="props.value" />
                        </q-avatar>
                    </q-td>
                </template>

                <!-- FullName -->
                <template #body-cell-fullname="props">
                    <q-td :props="props">
                        <q-badge color="primary">{{ props.value }}</q-badge>
                    </q-td>
                </template>

                <!-- Company Name -->
                <template #body-cell-company_name="props">
                    <q-td :props="props">
                        <q-badge color="primary">{{ props.value }}</q-badge>
                    </q-td>
                </template>

                <!-- Type -->
                <template #body-cell-type="props">
                    <q-td :props="props">
                        <q-badge color="primary">{{ props.value }}</q-badge>
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
