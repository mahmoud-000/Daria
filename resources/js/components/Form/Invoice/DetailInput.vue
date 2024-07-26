<script setup>
import { ref, computed, watch, toRefs, nextTick } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { fireNotify } from "../../../utils/notify";
import { useInvoiceDetail } from "../../../composables/invoiceDetail";

const props = defineProps({
    warehouse: {
        required: true,
        default: () => null,
    },
    notInclude: {
        type: Array,
        required: false,
        default: () => [],
    },
    types: {
        type: Array,
        required: true,
        default: () => [],
    },
    details: {
        type: Array,
        required: true,
        default: () => [],
    },
    keyOfUnit: {
        type: String,
        required: true,
        default: () => "purchase",
    },
    costOrPrice: {
        type: String,
        required: true,
        default: () => "cost",
    },
    errors: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const { warehouse, notInclude, types, details, keyOfUnit, costOrPrice } =
    toRefs(props);

const { t } = useI18n();
const store = useStore();

const { detailCalculate } = useInvoiceDetail(costOrPrice.value);

const itemSelect = ref(null);

const stock = computed(() => store.getters["stock/getStockByWarehouse"]);
const stockMeta = computed(() => store.getters["stock/getMeta"]);
const colorBasedOnMode = computed(() => store.getters["colorBasedOnMode"]);

const options = ref(null);
const loading = ref(false);
const detailInputRef = ref("");

const filterFn = (val, update, abort) => {
    if (val === "") {
        update(() => {
            options.value = stock.value;
        });
        return;
    }

    update(async () => {
        if (warehouse.value === null) {
            fireNotify("negative", t("messages.select_warehouse"));
            return;
        }

        const needle = val.toLowerCase();

        options.value = stock.value.filter(
            (v) =>
                v.item?.code.toLowerCase().indexOf(needle) > -1 ||
                v.variant?.code.toLowerCase().indexOf(needle) > -1 ||
                (v.item.type === 2
                    ? v.variant?.name.toLowerCase().indexOf(needle) > -1
                    : v.item?.name.toLowerCase().indexOf(needle) > -1)
        );

        if (
            !options.value.length ||
            stockMeta.value.current_page !== stockMeta.value.last_page
        ) {
            loading.value = true;
            // MAke a new request for stock
            await store.dispatch("stock/fetchStockByWarehouse", {
                warehouse: warehouse.value,
                not_include: notInclude.value,
                types: types.value,
                invoice_type: keyOfUnit.value,
                search: needle,
            });

            loading.value = false;
            update(() => {
                options.value = stock.value;
            });
        }

        if (
            val !== "" &&
            val.length > 3 &&
            options.value.length === 1 &&
            stockMeta.value.current_page === stockMeta.value.last_page
        ) {
            detailInputRef.value.moveOptionSelection(1, true); // focus the first selectable option and do not update the input-value
            detailInputRef.value.toggleOption(
                detailInputRef.value.options[detailInputRef.value.optionIndex],
                true
            ); // toggle the focused option

            itemSelect.value = options.value[0];
            nextTick(() => {
                if (addItemToDetailsTable()) {
                    itemSelect.value = null;
                    detailInputRef.value.updateInputValue("");
                }
            });
        }
    });
};

const addItemToDetailsTable = () => {
    if (
        details.value.some(
            (d) =>
                (itemSelect.value.item.product_type !== 2 &&
                    itemSelect.value.item_id === d.item_id &&
                    itemSelect.value.variant_id === d.variant_id) ||
                (itemSelect.value.item.product_type === 2 &&
                    itemSelect.value.item_id === d.item_id &&
                    itemSelect.value.variant_id === d.variant_id &&
                    new Date(itemSelect.value.production_date)
                        .toISOString()
                        .slice(0, 10) ===
                        new Date(d.production_date)
                            .toISOString()
                            .slice(0, 10) &&
                    new Date(itemSelect.value.expired_date)
                        .toISOString()
                        .slice(0, 10) ===
                        new Date(d.expired_date).toISOString().slice(0, 10))
        )
    ) {
        fireNotify("negative", t("messages.item_already_added"));
    } else {
        const detail = ref({});
        detail.value[`is_available_for_edit_in_${keyOfUnit.value}`] =
            itemSelect.value.item[
                `is_available_for_edit_in_${keyOfUnit.value}`
            ];
        detail.value.product_type = itemSelect.value.item.product_type;
        detail.value.type = itemSelect.value.item.type;
        detail.value.patches = itemSelect.value.patches;
        detail.value.production_date =
            itemSelect.value.item.product_type === 2
                ? new Date().toISOString().slice(0, 10)
                : null;
        detail.value.expired_date =
            itemSelect.value.item.product_type === 2
                ? new Date().toISOString().slice(0, 10)
                : null;
        detail.value.name = itemSelect.value.item.name;
        detail.value.code =
            itemSelect.value.item.type === 2
                ? itemSelect.value.variant.code
                : itemSelect.value.item.code;
        detail.value.variant = itemSelect.value.variant?.name;
        detail.value.warehouse_id = warehouse.value;
        detail.value.item_id = itemSelect.value.item_id;
        detail.value.variant_id = itemSelect.value.variant_id;
        detail.value.tax = itemSelect.value.item.tax;
        detail.value.discount_type = 1; // 1-fixed 2-percent
        detail.value.discount = 0;
        detail.value.discount_net = 0;
        detail.value.quantity = 1;
        detail.value.tax_type = itemSelect.value.item.tax_type;
        detail.value.tax_net =
            itemSelect.value.tax_details[`tax_${costOrPrice.value}`];
        detail.value.amount =
            itemSelect.value.tax_details[`unit_${costOrPrice.value}`];
        detail.value[`net_${costOrPrice.value}`] =
            itemSelect.value.tax_details[`net_${costOrPrice.value}`];
        detail.value.stock = itemSelect.value.stock;
        detail.value.total =
            itemSelect.value.tax_details[`total_${costOrPrice.value}`];
        detail.value.unit_id =
            itemSelect.value.item[`${keyOfUnit.value}_unit_id`];

        detail.value.unit =
            itemSelect.value.item.type !== 3
                ? itemSelect.value.item[`${keyOfUnit.value}_unit`][`short_name`]
                : "";
        detail.value.operator =
            itemSelect.value.item.type !== 3
                ? itemSelect.value.item[`${keyOfUnit.value}_unit`][`operator`]
                : "*";
        detail.value.operator_value =
            itemSelect.value.item.type !== 3
                ? itemSelect.value.item[`${keyOfUnit.value}_unit`][
                      `operator_value`
                  ]
                : 1;

        detail.value.movement = 1; // 1-Add 2-Sub For Adjustment
        // Fixed Values
        detail.value.unity =
            itemSelect.value.item.type !== 3
                ? itemSelect.value.item[`unit_id`]
                : "";
        detail.value.amounty =
            itemSelect.value.item.type === 2
                ? itemSelect.value.variant[`${costOrPrice.value}`]
                : itemSelect.value.item[`${costOrPrice.value}`];

        detailCalculate(detail.value);
        details.value.push(detail.value);
    }
    return true;
};

watch(
    warehouse,
    (new_warehouse) => {
        if (new_warehouse) {
            store.commit("stock/SET_STOCK_BY_WAREHOUSE", []);
            store.dispatch("stock/fetchStockByWarehouse", {
                warehouse: new_warehouse,
                not_include: notInclude.value,
                types: types.value,
                invoice_type: keyOfUnit.value,
            });
        }
    },
    {
        immediate: true,
    }
);
</script>

<template>
    <q-select
        v-model="itemSelect"
        :placeholder="t('messages.type_item_name')"
        ref="detailInputRef"
        :options="options"
        @filter="filterFn"
        :loading="loading"
        use-input
        hide-selected
        input-debounce="500"
        dense
        hide-hint
        hide-bottom-space
        outlined
        rounded
        options-cover
        options-dense
        transition-show="jump-up"
        transition-hide="jump-up"
        behavior="menu"
        :color="colorBasedOnMode"
    >
        <template #no-option>
            <q-item>
                <q-item-section class="text-italic text-grey">
                    {{ t("messages.no_options") }}
                </q-item-section>
            </q-item>
        </template>

        <template #option="scope">
            <q-item v-bind="scope.itemProps" @click="addItemToDetailsTable">
                <q-item-section avatar>
                    <q-avatar size="md">
                        <q-img :src="scope.opt.item.active_image.url" />
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label>
                        {{
                            scope.opt.variant
                                ? `${scope.opt.item.name} - ${scope.opt.variant.name}`
                                : scope.opt.item.name
                        }}
                    </q-item-label>
                    <q-item-label caption>
                        {{
                            scope.opt.variant
                                ? `${scope.opt.variant.code}`
                                : scope.opt.item.code
                        }}
                    </q-item-label>
                </q-item-section>
            </q-item>
        </template>
    </q-select>
</template>
