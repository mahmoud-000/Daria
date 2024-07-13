<script setup>
import { ref, toRefs, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useInvoiceDetail } from "../../../composables/invoiceDetail";
import {
    BaseInput,
    BaseBtn,
    SelectInput,
    DialogConfirm,
    DialogForm,
} from "../../import";
import { useRoute } from "vue-router";
import { adjustmentTypes } from "../../../utils/constraints";
import { floatify } from "../../../utils/helpers";
import { numberFormatWithCurrency } from "../../../utils/helpers";

const props = defineProps({
    details: {
        type: Array,
        required: true,
        default: () => {},
    },
    deletedDetails: {
        type: Array,
        required: true,
        default: () => [],
    },
    costOrPrice: {
        type: String,
        required: true,
        default: "cost",
    },
    keyOfUnit: {
        type: String,
        required: true,
        default: () => "purchase",
    },
});
const { details, deletedDetails, costOrPrice, keyOfUnit } = toRefs(props);

const { t } = useI18n();
const route = useRoute();

const emit = defineEmits(["close-confirm"]);

const deletedDetail = ref({});
const deletedDetailIndex = ref(null);
const dialogConfirm = ref(false);

const editedDetail = ref({});
const editedDetailIndex = ref(null);
const dialogForm = ref(false);

const confirmDelete = (detail, index) => {
    deletedDetail.value = detail;
    deletedDetailIndex.value = index;
    dialogConfirm.value = true;
};

const deleteItem = () => {
    if (deletedDetail.value) {
        deletedDetails.value.push(deletedDetail.value);
        details.value.splice(deletedDetailIndex.value, 1);

        dialogConfirm.value = false;
    }
};

const confirmEdit = (detail, index) => {
    editedDetail.value = detail;

    editedDetailIndex.value = index;
    dialogForm.value = true;
};

const { detailCalculate } = useInvoiceDetail(costOrPrice.value);

const editItem = (editForm) => {
    const detail = details.value.find((_, i) => i === editedDetailIndex.value);

    detail.amount =
        editForm.amount < 0 ? editForm.amount * -1 : editForm.amount;
    detail.tax = editForm.tax < 0 ? editForm.tax * -1 : editForm.tax;
    detail.tax_type = editForm.tax_type;
    detail.discount =
        editForm.discount < 0 ? editForm.discount * -1 : editForm.discount;
    detail.discount_type = editForm.discount_type;
    detail.unit_id = editForm.unit_id;
    detail.patch_id = editForm.patch_id;
    detail.production_date = editForm.production_date;
    detail.expired_date = editForm.expired_date;

    detailCalculate(detail);

    dialogForm.value = false;
};

const clearDeletArray = () => {
    deletedDetail.value = null;
};

const notAllowed = computed(
    () => !["adjustment.create", "adjustment.edit"].includes(route.name)
);

const increment = (detail) => {
    detail.quantity++;
    detailCalculate(detail);
};

const decrement = (detail) => {
    detail.quantity--;
    detail.quantity < 1 ? (detail.quantity = 1) : detail.quantity;
    detailCalculate(detail);
};
</script>

<template>
    <div class="col-12">
        <div class="col-12">
            <q-markup-table dense flat>
                <thead>
                    <tr>
                        <th class="text-center col-1">#</th>
                        <th class="text-center col-2">
                            {{ t("table.item") }}
                        </th>
                        <th class="text-center col-1" v-if="notAllowed">
                            {{ t(`table.net_unit_${costOrPrice}`) }}
                        </th>
                        <th class="text-center col-1">
                            {{ t("table.stock") }}
                        </th>
                        <th class="text-center col-6">
                            {{ t("table.quantity") }}
                        </th>
                        <th class="text-center col-2" v-if="!notAllowed">
                            {{ t("table.type") }}
                        </th>
                        <th class="text-center col-1" v-if="notAllowed">
                            {{ t("table.discount") }}
                        </th>
                        <th class="text-center col-1" v-if="notAllowed">
                            {{ t("table.tax") }}
                        </th>
                        <th class="text-center col-1" v-if="notAllowed">
                            {{ t("table.sub_total") }}
                        </th>
                        <th class="text-center col-1">
                            {{ t("table.actions") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(detail, i) in details" :key="i">
                        <td class="text-center">
                            <span>{{ i + 1 }}</span>
                        </td>
                        <td class="text-center">
                            {{ `${detail.name}` }}
                            <q-chip color="warning" size="sm">
                                {{ `${detail.code}` }}
                            </q-chip>
                            <q-chip
                                v-if="detail.variant"
                                color="secondary"
                                size="sm"
                            >
                                {{ detail.variant }}
                            </q-chip>

                            <div>
                                <q-chip
                                    v-if="detail.product_type === 2"
                                    color="positive"
                                    size="sm"
                                >
                                    {{ detail.production_date }}
                                </q-chip>
                                <span
                                    v-if="detail.product_type === 2"
                                    class="text-bold"
                                >
                                    -
                                </span>
                                <q-chip
                                    v-if="detail.product_type === 2"
                                    color="negative"
                                    size="sm"
                                >
                                    {{ detail.expired_date }}
                                </q-chip>
                            </div>
                        </td>

                        <td class="text-center" v-if="notAllowed">
                            {{
                                numberFormatWithCurrency(
                                    detail[`net_${costOrPrice}`],
                                    3
                                )
                            }}
                        </td>
                        <td class="text-center">
                            <q-chip color="primary" size="sm">
                                {{ detail.stocky }}
                                {{ detail.unit }}
                            </q-chip>
                        </td>
                        <td class="text-center" style="width: 130px">
                            <BaseInput
                                v-model.number="detail.quantity"
                                style="
                                    max-width: 130px;
                                    min-width: 130px;
                                    text-align: center;
                                "
                                @update:modelValue="
                                    () => detailCalculate(detail)
                                "
                                type="number"
                                min="1"
                                class="qte_input"
                            >
                                <template #before>
                                    <q-icon
                                        name="fa-solid fa-minus"
                                        class="bg-primary"
                                        @click="decrement(detail)"
                                    />
                                </template>
                                <template #after>
                                    <q-icon
                                        name="fa-solid fa-plus"
                                        class="bg-primary"
                                        @click="increment(detail)"
                                    />
                                </template>
                            </BaseInput>
                        </td>
                        <td class="text-center" v-if="!notAllowed">
                            <SelectInput
                                v-model="detail.type"
                                :options="adjustmentTypes"
                            />
                        </td>
                        <td class="text-center" v-if="notAllowed">
                            <span>
                                {{
                                    numberFormatWithCurrency(
                                        detail.discount_net
                                    )
                                }}
                            </span>
                        </td>
                        <td class="text-center" v-if="notAllowed">
                            <span>
                                {{ numberFormatWithCurrency(detail.tax_net) }}
                            </span>
                        </td>
                        <td class="text-center" v-if="notAllowed">
                            <span>
                                {{ numberFormatWithCurrency(detail.total) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <BaseBtn
                                glossy
                                round
                                icon="fa-solid fa-remove"
                                color="negative"
                                class="q-ml-sm"
                                :toolbar="t('table.delete_record')"
                                @click="confirmDelete(detail, i)"
                            />
                       
                            <BaseBtn
                                glossy
                                round
                                icon="fa-regular fa-pen-to-square"
                                color="positive"
                                class="q-ml-sm"
                                :toolbar="t('table.edit_record')"
                                @click="confirmEdit(detail, i)"
                                v-if="
                                    detail[
                                        `is_available_for_edit_in_${keyOfUnit}`
                                    ]
                                "
                            />
                        </td>
                    </tr>
                </tbody>
            </q-markup-table>
        </div>
    </div>

    <DialogForm
        v-model="dialogForm"
        v-if="dialogForm"
        :key-of-unit="keyOfUnit"
        :edited-detail="editedDetail"
        @edit-item="editItem"
    />

    <DialogConfirm
        @close-confirm="clearDeletArray"
        @delete-item="deleteItem"
        v-model="dialogConfirm"
        v-if="dialogConfirm"
    >
        {{ t("messages.delete_confirm") }}
    </DialogConfirm>
</template>

<style>
/* Chrome, Safari, Edge, Opera */
.qte_input input[type="number"]::-webkit-outer-spin-button,
.qte_input input[type="number"]::-webkit-inner-spin-button {
    appearance: none;
    margin: 0;
}

/* Firefox */
.qte_input input[type="number"] {
    appearance: textfield;
    text-align: center;
}
</style>
