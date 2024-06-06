<script setup>
import { ref, computed, toRefs, watch } from "vue";
import useVuelidate from "@vuelidate/core";
import {
    required,
    requiredIf,
    minValue,
    integer,
} from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";

import {
    BaseInput,
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    CardUpload,
    DateInput,
    SelectInput,
    DetailInput,
    DetailsTable,
    CalculateTable,
    SupplierInput,
    DelegateInput,
} from "../../../components/import";
import { fpTypes } from "../../../utils/constraints";
import { getSystemCurrencySymbol } from "../../../utils/helpers";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const store = useStore();

const keyOfUnit = ref("purchase");
const costOrPrice = ref("cost");
const SERVICE = 3;

const warehouses = computed(() => store.getters["warehouse/getOptions"]);
const pipelines = computed(() => store.getters["pipeline/getOptions"]);

const props = defineProps({
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
});

const { formData } = toRefs(props);

const rules = computed(() => ({
    date: { required },
    supplier_id: { required, integer },
    warehouse_id: { required, integer },
    tax: { required, minValue: minValue(0) },
    discount: { required, minValue: minValue(0) },
    other_expenses: { required, minValue: minValue(0) },
    shipping: {
        required,
        minValue: minValue(0),
    },
    pipeline_id: { required, integer },
    stage_id: { required, integer },
    discount_type: { required },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async (test) => {
    const validate = await $v.value.$validate();
    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("purchase/updatePurchase", formData.value);
        router.push({ name: "purchase.list" });
    }
    if (!route.params.id) {
        await store.dispatch("purchase/createPurchase", formData.value);
        router.push({ name: "purchase.list" });
    }
};

const stages = computed(
    () =>
        pipelines.value.find((p) => p.id === formData.value.pipeline_id)?.stages
);

watch(
    () => formData.value.delegate_id,
    (val) => {
        if (!val) {
            formData.value.commission_type = 1;
            formData.value.commission = 0;
            formData.value.shipping = 0;
            return;
        }

        const delegates = computed(() => store.getters["delegate/getOptions"]);

        const delegate = delegates.value?.find((d) => d.id === val);

        if (delegate && !!delegate.commission_type) {
            formData.value.commission_type = delegate.commission_type;
            formData.value.shipping = delegate.commission;
        }
    }
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
            :reference="formData.ref"
            :item-id="formData?.id"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        />
        <q-form @submit="onSubmit">
            <div class="row q-col-gutter-md q-mt-xs">
                <!-- General Informations -->
                <CardSectionWithHeader title="card.general_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <DateInput
                                v-model="formData.date"
                                :label="t('date')"
                                :error="$v.date.$error"
                                :errors="$v.date.$errors"
                                @input="() => $v.date.$touch()"
                                @blur="() => $v.date.$touch()"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SupplierInput
                                v-model="formData.supplier_id"
                                :error="$v.supplier_id.$error"
                                :errors="$v.supplier_id.$errors"
                                @input="() => $v.supplier_id.$touch()"
                                @blur="() => $v.supplier_id.$touch()"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.warehouse_id"
                                :label="t('warehouse_id')"
                                :options="
                                    warehouses.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.warehouse_id.$error"
                                :errors="$v.warehouse_id.$errors"
                                @input="() => $v.warehouse_id.$touch()"
                                @blur="() => $v.warehouse_id.$touch()"
                                :disable="formData.details.length > 0"
                            />
                        </div>

                        <div
                            class="col-lg-3 col-md-3 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.doc_invoice_number"
                                :label="t('doc_invoice_number')"
                            />
                        </div>

                        <div
                            class="col-lg-9 col-md-9 col-xs-12 q-px-md q-pb-sm"
                        >
                            <DetailInput
                                :warehouse="formData.warehouse_id"
                                :details="formData.details"
                                :cost-or-price="costOrPrice"
                                :key-of-unit="keyOfUnit"
                                :not-include="[SERVICE]"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Details Informations -->
                <CardSectionWithHeader title="card.details_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <DetailsTable
                            :details="formData.details"
                            :deleted-details="formData.deletedDetails"
                            :cost-or-price="costOrPrice"
                            :key-of-unit="keyOfUnit"
                        />
                    </div>
                </CardSectionWithHeader>

                <!-- Calculate Informations -->
                <CardSectionWithHeader title="card.calculate_info">
                    <div
                        class="row justify-end q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <CalculateTable
                            :formData="formData"
                            :cost-or-price="costOrPrice"
                        />
                    </div>
                </CardSectionWithHeader>

                <!-- Expenses Informations -->
                <CardSectionWithHeader title="card.expenses_info">
                    <div
                        class="row q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
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
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
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
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model.number="formData.discount"
                                :label="t('discount')"
                                :error="$v.discount.$error"
                                @input="() => $v.discount.$touch()"
                                @blur="() => $v.discount.$touch()"
                                :errors="$v.discount.$errors"
                                min="0"
                                :prefix="getSystemCurrencySymbol"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model.number="formData.other_expenses"
                                :label="t('other_expenses')"
                                :error="$v.other_expenses.$error"
                                @input="() => $v.other_expenses.$touch()"
                                @blur="() => $v.other_expenses.$touch()"
                                :errors="$v.other_expenses.$errors"
                                min="0"
                                :prefix="getSystemCurrencySymbol"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <DelegateInput v-model="formData.delegate_id" />
                        </div>

                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model.number="formData.shipping"
                                :label="t('shipping')"
                                :error="$v.shipping.$error"
                                @input="() => $v.shipping.$touch()"
                                @blur="() => $v.shipping.$touch()"
                                :errors="$v.shipping.$errors"
                                min="0"
                                :prefix="
                                    formData.commission_type === 1
                                        ? getSystemCurrencySymbol
                                        : '%'
                                "
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Status Informations -->
                <CardSectionWithHeader title="card.status_info">
                    <div
                        class="row q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.pipeline_id"
                                :label="t('pipeline_id')"
                                :options="
                                    pipelines.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.pipeline_id.$error"
                                :errors="$v.pipeline_id.$errors"
                                @input="() => $v.pipeline_id.$touch()"
                                @blur="() => $v.pipeline_id.$touch()"
                            />
                        </div>

                        <div
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.stage_id"
                                :label="t('stage_id')"
                                :options="
                                    stages?.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.stage_id.$error"
                                :errors="$v.stage_id.$errors"
                                @input="() => $v.stage_id.$touch()"
                                @blur="() => $v.stage_id.$touch()"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Documents -->
                <CardUpload
                    :form-data="formData"
                    title="card.documents"
                    :config="{
                        keyOfImages: 'purchase_documents',
                        maxFiles: 5,
                        allowMultiple: true,
                        allowReorder: true,
                        label: 'choose_your_docs',
                        acceptedTypes: 'file/pdf, file/doc',
                    }"
                />
                <!-- Remarks -->
                <CardRemarks :form-data="formData" />
            </div>
        </q-form>
    </q-card>
</template>
