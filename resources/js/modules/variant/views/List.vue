<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";
import { numberFormatWithCurrency } from "../../../utils/helpers";

const config = reactive({
    moduleName: "variant",
    fetchItems: "fetchVariants",
    getItems: "getVariants",
    destroyItem: "destroyVariant",
    bulkDestroyItems: "bulkDestroyVariants",
    options: {
        export: true,
    }, // import csv - export csv
});
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <!-- Image -->
                <template #body-cell-image="props">
                    <q-td class="text-center" :props="props">
                        <q-avatar size="md">
                            <q-img :src="props.value" />
                        </q-avatar>
                    </q-td>
                </template>

                <template #body-cell-item="props">
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
                                                    props.row.item?.active_image.url
                                                "
                                            />
                                        </q-avatar>
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-td>
                </template>

                <!-- Cost -->
                <template #body-cell-cost="props">
                    <q-td class="text-center" :props="props">
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>
                </template>

                <!-- Price -->
                <template #body-cell-price="props">
                    <q-td class="text-center" :props="props">
                        {{ numberFormatWithCurrency(props.value) }}
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
