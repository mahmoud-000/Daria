<script setup>
import { ref, computed, toRefs, watch } from "vue";
import useVuelidate from "@vuelidate/core";
import { helpers } from "@vuelidate/validators";
import {
    required,
    requiredIf,
    minValue,
    minLength,
    maxLength,
} from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";

import {
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    CardVariants,
    CardUpload,
    BaseInput,
    SelectInput,
    CategoryInput,
    BrandInput,
    CurrencyInput,
} from "../../../components/import";

import {
    itemTypes,
    productTypes,
    taxTypes,
    barcodeTypes,
} from "../../../utils/constraints";
import { getDefaultCurrencySymbol } from "../../../utils/helpers";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const store = useStore();

await store.dispatch("item/fetchFormOptions");

const getBaseUnits = computed(() => store.getters["unit/getBaseUnits"]);
const getUnitWithChilds = computed(() =>
    store.getters["unit/getUnitWithChilds"](formData.value.unit_id)
);

const props = defineProps({
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
});

const STANDARD = 1;
const VARIABLE = 2;
const SERVICE = 3;

const { formData } = toRefs(props);

const rules = computed(() => ({
    name: { required, minLength: minLength(3), maxLength: maxLength(100) },
    label: { required, minLength: minLength(3), maxLength: maxLength(100) },
    item_desc: { maxLength: maxLength(255) },
    category_id: { required },
    cost: {
        requiredIfRef: requiredIf(formData.value.type === STANDARD),
        minValue: minValue(0),
    },
    price: { required, minValue: minValue(0) },
    barcode_type: { required },
    sku: { required, minLength: minLength(8) },
    code: { required, minLength: minLength(8) },
    tax_type: { required },
    tax: {
        minValue: minValue(0),
        required,
    },
    stock_alert: { minValue: minValue(0) },
    unit_id: {
        requiredIfRef: requiredIf(
            [STANDARD, VARIABLE].includes(formData.value.type)
        ),
    },
    purchase_unit_id: {
        requiredIfRef: requiredIf(
            [STANDARD, VARIABLE].includes(formData.value.type)
        ),
    },
    sale_unit_id: {
        requiredIfRef: requiredIf(
            [STANDARD, VARIABLE].includes(formData.value.type)
        ),
    },
    type: { required },
    product_type: { required },
    is_active: { required },
    variants: {
        $each: helpers.forEach({
            name: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(100),
            },
            cost: { required, minValue: minValue(0) },
            price: { required, minValue: minValue(0) },
            code: { required, minLength: minLength(8) },
            sku: { required, minLength: minLength(8) },
            is_active: { required },
        }),
    },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("item/updateItem", formData.value);
        router.push({ name: "item.list" });
    }
    if (!route.params.id) {
        await store.dispatch("item/createItem", formData.value);
        router.push({ name: "item.list" });
    }
};

watch(
    () => formData.value.type,
    (val) => {
        if (val === SERVICE) {
            formData.value.is_available_for_purchase = 0;
            formData.value.is_available_for_edit_in_purchase = 0;
            formData.value.product_type = 1;
        }
    },
    { immediate: true }
);
</script>

<template>
    <q-card
        class="bg-transparent"
        flat
        style="
            max-width: 70rem;
            margin-left: auto;
            margin-right: auto;
            border-radius: 20px;
        "
    >
        <CardHeader
            @on-submit="onSubmit"
            :reference="formData.name"
            :item-id="formData?.id"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        />
        <q-form @submit="onSubmit">
            <div class="row q-col-gutter-md q-mt-xs">
                <!-- Images -->
                <CardUpload
                    title="card.images"
                    :form-data="formData"
                    :config="{
                        keyOfImages: 'item_images',
                        maxFiles: 5,
                        allowMultiple: true,
                        allowReorder: true,
                        label: 'choose_your_images',
                    }"
                />
                <!-- General Information -->
                <CardSectionWithHeader title="card.general_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-4 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.name"
                                :label="t('name')"
                                :error="$v.name.$error"
                                @input="() => $v.name.$touch()"
                                @blur="() => $v.name.$touch()"
                                :errors="$v.name.$errors"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.sku"
                                :label="t('sku')"
                                :error="$v.sku.$error"
                                @input="() => $v.sku.$touch()"
                                @blur="() => $v.sku.$touch()"
                                :errors="$v.sku.$errors"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.label"
                                :label="t('label')"
                                :error="$v.label.$error"
                                @input="() => $v.label.$touch()"
                                @blur="() => $v.label.$touch()"
                                :errors="$v.label.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-12 col-md-12 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.item_desc"
                                :label="t('item_desc')"
                                type="textarea"
                                counter
                                maxlength="255"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <CategoryInput
                                v-model="formData.category_id"
                                :error="$v.category_id.$error"
                                @input="() => $v.category_id.$touch()"
                                @blur="() => $v.category_id.$touch()"
                                :errors="$v.category_id.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BrandInput v-model="formData.brand_id" />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.barcode_type"
                                :label="t('barcode_type')"
                                :options="barcodeTypes"
                                :error="$v.barcode_type.$error"
                                :errors="$v.barcode_type.$errors"
                                @input="() => $v.barcode_type.$touch()"
                                @blur="() => $v.barcode_type.$touch()"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.code"
                                :label="t('code')"
                                :error="$v.code.$error"
                                @input="() => $v.code.$touch()"
                                @blur="() => $v.code.$touch()"
                                :errors="$v.code.$errors"
                            >
                                <template #append>
                                    <q-icon
                                        name="fa-solid fa-barcode"
                                        class="bg-primary"
                                        @click="
                                            formData.code = Math.random()
                                                .toString()
                                                .slice(2, 10)
                                        "
                                    />
                                </template>
                            </BaseInput>
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model.number="formData.tax"
                                :label="t('tax')"
                                :error="$v.tax.$error"
                                @input="() => $v.tax.$touch()"
                                @blur="() => $v.tax.$touch()"
                                :errors="$v.tax.$errors"
                                min="0"
                                prefix="%"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
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

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <q-toggle
                                dense
                                color="primary"
                                v-model="formData.is_active"
                                :trueValue="1"
                                :falseValue="0"
                                checked-icon="fa-solid fa-check"
                                unchecked-icon="fa-solid fa-xmark"
                                :label="t('is_active')"
                                :error="$v.is_active.$error"
                                @input="() => $v.is_active.$touch()"
                                @blur="() => $v.is_active.$touch()"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="
                                formData.is_active && formData.type !== SERVICE
                            "
                        >
                            <q-toggle
                                dense
                                color="primary"
                                v-model="formData.is_available_for_purchase"
                                :trueValue="1"
                                :falseValue="0"
                                checked-icon="fa-solid fa-check"
                                unchecked-icon="fa-solid fa-xmark"
                                :label="t('is_available_for_purchase')"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="formData.is_active"
                        >
                            <q-toggle
                                dense
                                color="primary"
                                v-model="formData.is_available_for_sale"
                                :trueValue="1"
                                :falseValue="0"
                                checked-icon="fa-solid fa-check"
                                unchecked-icon="fa-solid fa-xmark"
                                :label="t('is_available_for_sale')"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="
                                formData.is_active && formData.type !== SERVICE
                            "
                        >
                            <q-toggle
                                dense
                                color="primary"
                                v-model="
                                    formData.is_available_for_edit_in_purchase
                                "
                                :trueValue="1"
                                :falseValue="0"
                                checked-icon="fa-solid fa-check"
                                unchecked-icon="fa-solid fa-xmark"
                                :label="t('is_available_for_edit_in_purchase')"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="formData.is_active"
                        >
                            <q-toggle
                                dense
                                color="primary"
                                v-model="formData.is_available_for_edit_in_sale"
                                :trueValue="1"
                                :falseValue="0"
                                checked-icon="fa-solid fa-check"
                                unchecked-icon="fa-solid fa-xmark"
                                :label="t('is_available_for_edit_in_sale')"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Item Type Information -->
                <CardSectionWithHeader title="card.item_type_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.type"
                                :label="t('type')"
                                :options="itemTypes"
                                :error="$v.type.$error"
                                :errors="$v.type.$errors"
                                @input="() => $v.type.$touch()"
                                @blur="() => $v.type.$touch()"
                                :disable="!!route.params.id"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="formData.type !== SERVICE"
                        >
                            <SelectInput
                                v-model="formData.product_type"
                                :label="t('product_type')"
                                :options="productTypes"
                                :error="$v.product_type.$error"
                                :errors="$v.product_type.$errors"
                                @input="() => $v.product_type.$touch()"
                                @blur="() => $v.product_type.$touch()"
                                :disable="!!route.params.id"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="[STANDARD, VARIABLE].includes(formData.type)"
                        >
                            <SelectInput
                                v-model="formData.unit_id"
                                :label="t('unit_id')"
                                :options="
                                    getBaseUnits?.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.unit_id.$error"
                                :errors="$v.unit_id.$errors"
                                @input="() => $v.unit_id.$touch()"
                                @blur="() => $v.unit_id.$touch()"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="[STANDARD, VARIABLE].includes(formData.type)"
                        >
                            <SelectInput
                                v-model="formData.purchase_unit_id"
                                :label="t('purchase_unit_id')"
                                :options="
                                    getUnitWithChilds?.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.purchase_unit_id.$error"
                                :errors="$v.purchase_unit_id.$errors"
                                @input="() => $v.purchase_unit_id.$touch()"
                                @blur="() => $v.purchase_unit_id.$touch()"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="[STANDARD, VARIABLE].includes(formData.type)"
                        >
                            <SelectInput
                                v-model="formData.sale_unit_id"
                                :label="t('sale_unit_id')"
                                :options="
                                    getUnitWithChilds?.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.sale_unit_id.$error"
                                :errors="$v.sale_unit_id.$errors"
                                @input="() => $v.sale_unit_id.$touch()"
                                @blur="() => $v.sale_unit_id.$touch()"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="[STANDARD].includes(formData.type)"
                        >
                            <BaseInput
                                v-model.number="formData.cost"
                                :label="t('cost')"
                                :error="$v.cost.$error"
                                @input="() => $v.cost.$touch()"
                                @blur="() => $v.cost.$touch()"
                                :errors="$v.cost.$errors"
                                min="0"
                                :prefix="getDefaultCurrencySymbol"
                            />
                            <!-- mask="#.##"
                                fill-mask="0"
                                reverse-fill-mask
                                hint="Mask: #.##" -->
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="[STANDARD, SERVICE].includes(formData.type)"
                        >
                            <BaseInput
                                v-model.number="formData.price"
                                :label="t('price')"
                                :error="$v.price.$error"
                                @input="() => $v.price.$touch()"
                                @blur="() => $v.price.$touch()"
                                :errors="$v.price.$errors"
                                min="0"
                                :prefix="getDefaultCurrencySymbol"
                            />
                            <!-- mask="#.##"
                                fill-mask="0"
                                reverse-fill-mask
                                hint="Mask: #.##" -->
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Variants -->
                <CardVariants
                    :form-data="formData"
                    :v="$v"
                    v-if="[VARIABLE].includes(formData.type)"
                />

                <!-- Stock -->
                <CardSectionWithHeader title="card.stock_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.stock_alert"
                                :label="t('stock_alert')"
                                :error="$v.stock_alert.$error"
                                @input="() => $v.stock_alert.$touch()"
                                @blur="() => $v.stock_alert.$touch()"
                                :errors="$v.stock_alert.$errors"
                                type="number"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Remarks -->
                <CardRemarks :form-data="formData" />
            </div>
        </q-form>
    </q-card>
</template>
