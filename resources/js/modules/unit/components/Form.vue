<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
import {
    required,
    minLength,
    maxLength,
    requiredIf,
} from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import { operators } from "../../../utils/constraints";

import {
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    BaseInput,
    SelectInput,
} from "../../../components/import";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const store = useStore();

const props = defineProps({
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
});

const { formData } = toRefs(props);

const rules = computed(() => ({
    name: { required, minLength: minLength(3), maxLength: maxLength(100) },
    short_name: { required, minLength: minLength(1), maxLength: maxLength(50) },
    operator: {
        requiredIfRef: requiredIf(formData.value.unit_id),
    },
    operator_value: {
        requiredIf: requiredIf(formData.unit_id),
    },
    is_active: { required },
}));

const $v = useVuelidate(rules, formData);

await store.dispatch("unit/fetchOptions");
const getBaseUnits = computed(() => store.getters["unit/getBaseUnits"]);
const units = getBaseUnits.value.filter(
    (unitbase) => unitbase.id !== formData.value.id
);
const clearUnit = () => {
    formData.value.operator = null;
    formData.value.operator_value = null;
};

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("unit/updateUnit", formData.value);
        router.push({ name: "unit.list" });
    }
    if (!route.params.id) {
        await store.dispatch("unit/createUnit", formData.value);
        router.push({ name: "unit.list" });
    }
};
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
                <!-- General Information -->
                <CardSectionWithHeader title="card.general_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData[`name`]"
                                :label="t(`name`)"
                                :error="$v[`name`].$error"
                                @input="() => $v[`name`].$touch()"
                                @blur="() => $v[`name`].$touch()"
                                :errors="$v[`name`].$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.short_name"
                                :label="t('short_name')"
                                :error="$v.short_name.$error"
                                @input="() => $v.short_name.$touch()"
                                @blur="() => $v.short_name.$touch()"
                                :errors="$v.short_name.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.unit_id"
                                :label="t('unit_id')"
                                :options="
                                    units?.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                clearable
                                @clear="clearUnit"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="formData.unit_id"
                        >
                            <SelectInput
                                v-model="formData.operator"
                                :label="t('operator')"
                                :options="operators"
                                :error="$v.operator.$error"
                                @input="() => $v.operator.$touch()"
                                @blur="() => $v.operator.$touch()"
                                :errors="$v.operator.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            v-if="formData.unit_id"
                        >
                            <BaseInput
                                v-model.number="formData.operator_value"
                                :label="t('operator_value')"
                                :error="$v.operator_value.$error"
                                @input="() => $v.operator_value.$touch()"
                                @blur="() => $v.operator_value.$touch()"
                                :errors="$v.operator_value.$errors"
                                min="0"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <q-toggle
                                keep-color
                                v-model="formData.is_active"
                                :trueValue="1"
                                :falseValue="0"
                                :label="t('is_active')"
                                :error="$v.is_active.$error"
                                @input="() => $v.is_active.$touch()"
                                @blur="() => $v.is_active.$touch()"
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
