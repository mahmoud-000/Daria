<script setup>
import useVuelidate from "@vuelidate/core";
import { ref, reactive, toRefs, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { taxTypes, fpTypes } from "../../utils/constraints";
import { required, requiredIf, minValue } from "../../utils/i18n-validators";
import { BaseInput, BaseBtn, SelectInput, DateInput } from "../import";
import { getDefaultCurrencySymbol } from "../../utils/helpers";

const props = defineProps({
    dialogForm: {
        type: Boolean,
        default: () => false,
    },
    editedDetail: {
        type: Object,
        required: true,
        default: () => {},
    },
    keyOfUnit: {
        type: String,
        required: true,
        default: () => "purchase",
    },
});

const { editedDetail, keyOfUnit } = toRefs(props);

const maximizedToggle = ref(false);

const store = useStore();
const { t } = useI18n();

const formData = reactive({
    amount: 0,
    tax: 0,
    tax_type: null,
    discount: 0,
    discount_type: null,
    unit_id: null,
    product_type: 1,
    production_date: null,
    expired_date: null,
});

const rules = computed(() => ({
    amount: { required, minValue: minValue(0) },
    tax_type: { required },
    tax: {
        minValue: minValue(0),
        required,
    },
    discount_type: { required },
    discount: {
        minValue: minValue(0),
        required,
    },
    unit_id: { required },
    production_date: {
        requiredIfRef: requiredIf(formData.product_type === 2),
    },
    expired_date: {
        requiredIfRef: requiredIf(formData.product_type === 2),
    },
}));

const $v = useVuelidate(rules, formData);

const emit = defineEmits(["close-confirm", "edit-item"]);

const editItem = async (item) => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }
    emit("edit-item", item);
};

const getUnitWithChilds = computed(() =>
    store.getters["unit/getUnitWithChilds"](editedDetail.value.unity)
);

const updateUnit = () => {
    const unit = getUnitWithChilds.value.find(
        (unit) => unit.id === formData.unit_id
    );
    if (unit) {
        if (unit.operator === "/") {
            formData.amount = editedDetail.value.amounty / unit.operator_value;
        }

        if (unit.operator === "*") {
            formData.amount = editedDetail.value.amounty * unit.operator_value;
        }
    }
};

onMounted(() => {
    formData.amount = editedDetail.value.amount;
    formData.tax = editedDetail.value.tax;
    formData.tax_type = editedDetail.value.tax_type;
    formData.discount = editedDetail.value.discount;
    formData.discount_type = editedDetail.value.discount_type;
    formData.unit_id = editedDetail.value.unit_id;
    formData.product_type = editedDetail.value.product_type;
    formData.production_date = editedDetail.value.production_date;
    formData.expired_date = editedDetail.value.expired_date;
});
</script>
<template>
    <q-dialog
        :modelValue="dialogForm"
        @update:modelValue="(value) => (dialogForm = value)"
        persistent
        :maximized="maximizedToggle"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card flat style="width: 700px; max-width: 80vw">
            <q-bar>
                <q-space />

                <q-btn
                    dense
                    flat
                    icon="minimize"
                    @click="maximizedToggle = false"
                    :disable="!maximizedToggle"
                >
                    <q-tooltip v-if="maximizedToggle">{{
                        $t("action.minimize")
                    }}</q-tooltip>
                </q-btn>
                <q-btn
                    dense
                    flat
                    icon="crop_square"
                    @click="maximizedToggle = true"
                    :disable="maximizedToggle"
                >
                    <q-tooltip v-if="!maximizedToggle">{{
                        $t("action.maximize")
                    }}</q-tooltip>
                </q-btn>

                <q-btn
                    dense
                    flat
                    icon="close"
                    v-close-popup
                    @click="emit('close-confirm')"
                >
                    <q-tooltip>{{ $t("action.close") }}</q-tooltip>
                </q-btn>
            </q-bar>

            <q-card-section :class="!$q.dark.isActive ? 'bg-white' : 'bg-dark'">
                <div class="col-12">
                    <div class="text-h5 q-px-md">
                        {{
                            $t(`action.edit`, {
                                module: t(`links.item`),
                            })
                        }}
                    </div>
                </div>
            </q-card-section>

            <q-card-section class="text-center">
                <div class="row justify-between">
                    <div
                        class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        v-if="
                            formData.product_type === 2 && !editedDetail.id
                        "
                    >
                        <DateInput
                            v-model="formData.production_date"
                            :label="t('production_date')"
                            :error="$v.production_date.$error"
                            @input="() => $v.production_date.$touch()"
                            @blur="() => $v.production_date.$touch()"
                            :errors="$v.production_date.$errors"
                        />
                    </div>
                    <div
                        class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        v-if="
                            formData.product_type === 2 && !editedDetail.id
                        "
                    >
                        <DateInput
                            v-model="formData.expired_date"
                            :label="t('expired_date')"
                            :error="$v.expired_date.$error"
                            @input="() => $v.expired_date.$touch()"
                            @blur="() => $v.expired_date.$touch()"
                            :errors="$v.expired_date.$errors"
                        />
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput
                            v-model.number="formData.amount"
                            :label="t('cost')"
                            :error="$v.amount.$error"
                            @input="() => $v.amount.$touch()"
                            @blur="() => $v.amount.$touch()"
                            :errors="$v.amount.$errors"
                            min="0"
                            :prefix="getDefaultCurrencySymbol"
                        />
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput
                            v-model.number="formData.tax"
                            :label="t('tax')"
                            :error="$v.tax.$error"
                            @input="() => $v.tax.$touch()"
                            @blur="() => $v.tax.$touch()"
                            :errors="$v.tax.$errors"
                            min="0"
                            :prefix="getDefaultCurrencySymbol"
                        />
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <SelectInput
                            v-model="formData.tax_type"
                            :label="t('tax_type')"
                            :options="taxTypes"
                            :error="$v.tax_type.$error"
                            :errors="$v.tax_type.$errors"
                            @input="() => $v.tax_type.$touch()"
                            @blur="() => $v.tax_type.$touch()"
                        />
                    </div>

                    <div class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput
                            v-model.number="formData.discount"
                            :label="t('discount')"
                            :error="$v.discount.$error"
                            @input="() => $v.discount.$touch()"
                            @blur="() => $v.discount.$touch()"
                            :errors="$v.discount.$errors"
                            min="0"
                            :prefix="getDefaultCurrencySymbol"
                        />
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <SelectInput
                            v-model="formData.discount_type"
                            :label="t('discount_type')"
                            :options="fpTypes"
                            :error="$v.discount_type.$error"
                            :errors="$v.discount_type.$errors"
                            @input="() => $v.discount_type.$touch()"
                            @blur="() => $v.discount_type.$touch()"
                        />
                    </div>
            
                    <div
                        class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        v-if="!editedDetail.id"
                    >
                        <SelectInput
                            v-model="formData.unit_id"
                            :label="t(`${keyOfUnit}_unit_id`)"
                            :options="
                                getUnitWithChilds?.map((opt) => ({
                                    label: opt.name,
                                    value: opt.id,
                                }))
                            "
                            :error="$v.unit_id.$error"
                            :errors="$v.unit_id.$errors"
                            @input="() => $v.unit_id.$touch()"
                            @blur="() => $v.unit_id.$touch()"
                            @update:modelValue="updateUnit()"
                        />
                    </div>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <BaseBtn
                    :toolbar="$t('action.edit')"
                    @click="() => editItem(formData)"
                    icon="check"
                    color="positive"
                    q-close-popup
                    size="md"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
