<script setup>
import { reactive } from "vue";
import { columns } from "../columns";
import { BaseTable, TheSpinner } from "../../../components/import";
import { numberFormatWithCurrency } from "../../../utils/helpers";

const config = reactive({
    moduleName: "item",
    fetchItems: "fetchItems",
    getItems: "getItems",
    destroyItem: "destroyItem",
    bulkDestroyItems: "bulkDestroyItems",
    options: {
        export: true,
    }, // import csv - export csv
});

const STANDARD = 1;
const VARIABLE = 2;
const SERVICE = 3;
</script>
<template>
    <Suspense>
        <template #default>
            <BaseTable :config="config" :columns="columns">
                <!-- Active Image -->
                <template #body-cell-active_image="props">
                    <q-td class="text-center" :props="props">
                        <q-avatar size="md">
                            <q-img :src="props.value" />
                        </q-avatar>
                    </q-td>
                </template>

                <!-- Item Name -->
                <template #body-cell-name="props">
                    <q-td
                        :props="props"
                        v-if="[STANDARD, SERVICE].includes(props.row.type)"
                    >
                        <q-badge color="primary">{{ props.value }}</q-badge>
                    </q-td>

                    <q-td
                        :props="props"
                        v-else-if="props.row.type === VARIABLE"
                    >
                        <ul style="list-style: none">
                            <li
                                v-for="(variant, i) in props.row.variants"
                                :key="i"
                            >
                                <q-badge color="primary">{{
                                    variant.name
                                }}</q-badge>
                            </li>
                        </ul>
                    </q-td>

                    <q-td :props="props" v-else> - - - </q-td>
                </template>

                <!-- Item Type Badge -->
                <template #body-cell-type="props">
                    <q-td class="text-center" :props="props">
                        <q-badge color="primary">{{ props.value }}</q-badge>
                    </q-td>
                </template>

                <!-- Item Cost -->
                <template #body-cell-cost="props">
                    <q-td :props="props" v-if="props.row.type === STANDARD">
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>

                    <q-td
                        :props="props"
                        v-else-if="props.row.type === VARIABLE"
                    >
                        <ul style="list-style: none">
                            <li
                                v-for="(variant, i) in props.row.variants"
                                :key="i"
                            >
                                {{ numberFormatWithCurrency(variant.cost) }}
                            </li>
                        </ul>
                    </q-td>

                    <q-td :props="props" v-else> - - - </q-td>
                </template>

                <!-- Item Price -->
                <template #body-cell-price="props">
                    <q-td
                        :props="props"
                        v-if="[STANDARD, SERVICE].includes(props.row.type)"
                    >
                        {{ numberFormatWithCurrency(props.value) }}
                    </q-td>

                    <q-td
                        :props="props"
                        v-else-if="props.row.type === VARIABLE"
                    >
                        <ul style="list-style: none">
                            <li
                                v-for="(variant, i) in props.row.variants"
                                :key="i"
                            >
                                {{ numberFormatWithCurrency(variant.price) }}
                            </li>
                        </ul>
                    </q-td>

                    <q-td :props="props" v-else> - - - </q-td>
                </template>

                <!-- For Purchase Badge -->
                <template #body-cell-is_available_for_purchase="props">
                    <q-td class="text-center" :props="props">
                        <q-badge
                            :color="
                                props.row.is_available_for_purchase === 1
                                    ? 'positive'
                                    : 'negative'
                            "
                            class="q-ma-none"
                            >{{ props.value }}</q-badge
                        >
                    </q-td>
                </template>

                <!-- For Sale Badge -->
                <template #body-cell-is_available_for_sale="props">
                    <q-td class="text-center" :props="props">
                        <q-badge
                            :color="
                                props.row.is_available_for_sale === 1
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

<style scoped>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
</style>
