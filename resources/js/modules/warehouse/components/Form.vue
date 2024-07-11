<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
import {
    required,
    minLength,
    maxLength,
    email,
} from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import countries from "../../../utils/countries";

import {
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    BaseInput,
    CountryInput,
    SelectInput
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
    name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(100),
    },
    email: { email },
    is_active: { required },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("warehouse/updateWarehouse", formData.value);
        router.push({ name: "warehouse.list" });
    }
    if (!route.params.id) {
        await store.dispatch("warehouse/createWarehouse", formData.value);
        router.push({ name: "warehouse.list" });
    }
};

const changeCountry = () => {
    formData.value.city = null;
};

const cities = () =>
    countries.find(
        (country) =>
            country?.countryName === formData.value.country
    )?.regions;
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
                                v-model="formData[`email`]"
                                :label="t(`email`)"
                                :error="$v[`email`].$error"
                                @input="() => $v[`email`].$touch()"
                                @blur="() => $v[`email`].$touch()"
                                :errors="$v[`email`].$errors"
                                type="email"
                            />
                        </div>

                        <div
                            class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                :label="t('mobile')"
                                v-model="formData.mobile"
                            />
                        </div>

                        <div
                            class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                v-model="formData.phone"
                                :label="t('phone')"
                            />
                        </div>

                        <div
                            class="col-lg-3 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <CountryInput
                                v-model="formData.country"
                                :label="t('country')"
                                @update:modelValue="changeCountry()"
                                :location="formData"
                            />
                        </div>
                        <div
                            class="col-lg-3 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.city"
                                :label="t('city')"
                                :options="
                                    cities()?.map((opt) => ({
                                        label: opt.name,
                                        value: opt.name,
                                    }))
                                "
                            />
                        </div>
                        <div
                            class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.first_address"
                                :label="t('first_address')"
                            />
                        </div>
                        <div
                            class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.second_address"
                                :label="t('second_address')"
                            />
                        </div>
                        <div
                            class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.state"
                                :label="t('state')"
                            />
                        </div>
                        <div
                            class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.zip"
                                :label="t('zip')"
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
                                checked-icon="fa-solid fa-check"
                            unchecked-icon="fa-solid fa-xmark"
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
