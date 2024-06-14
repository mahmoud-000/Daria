<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
import { helpers } from "@vuelidate/validators";
import { required } from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";

import {
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    BaseInput,
    SelectInput,
    CountryInput,
    CardUpload,
    CurrencyInput,
    AddBtn,
    RemoveBtn,
} from "../../../components/import";
import { addTo, removeFrom } from "../../../utils/helpers";
import { branch } from "../../../utils/constraints";
import countries from "../../../utils/countries";

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
    name: { required },
    is_active: { required },
    currency: { required },
    branches: {
        $each: helpers.forEach({
            name: { required },
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
        await store.dispatch("company/updateCompany", formData.value);
        router.push({ name: "company.list" });
    }
    if (!route.params.id) {
        await store.dispatch("company/createCompany", formData.value);
        router.push({ name: "company.list" });
    }
};

const changeCountry = (i) => {
    formData.value.branches[i].city = null;
};

const cities = (i) =>
    countries.find(
        (country) => country?.countryName === formData.value.branches[i].country
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
                                v-model="formData.name"
                                :label="t(`name`)"
                                :error="$v.name.$error"
                                @input="() => $v.name.$touch()"
                                @blur="() => $v.name.$touch()"
                                :errors="$v.name.$errors"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <CurrencyInput
                                v-model="formData.currency"
                                :label="t('currency')"
                                :error="$v.currency.$error"
                                :errors="$v.currency.$errors"
                                @input="() => $v.currency.$touch()"
                                @blur="() => $v.currency.$touch()"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.vat_registration_number"
                                :label="t('vat_registration_number')"
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
                                checked-icon="check"
                                unchecked-icon="clear"
                                :label="t('is_active')"
                                :error="$v.is_active.$error"
                                @input="() => $v.is_active.$touch()"
                                @blur="() => $v.is_active.$touch()"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Branches -->
                <CardSectionWithHeader title="card.branches">
                    <template #btn>
                        <AddBtn
                            @click="() => addTo(formData, 'branches', branch)"
                        />
                    </template>

                    <div
                        v-for="(branch, i) in formData?.branches"
                        :key="i"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                        class="q-py-md"
                    >
                        <div class="row items-center justify-around">
                            <div
                                class="q-px-md q-pb-sm col-lg-4 col-md-4 col-xs-12"
                            >
                                <BaseInput
                                    :label="t('name')"
                                    v-model="branch.name"
                                    :error="
                                        Boolean(
                                            $v.branches.$each.$response.$errors[
                                                i
                                            ].name.length
                                        )
                                    "
                                />
                            </div>

                            <div
                                class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                            >
                                <CountryInput
                                    v-model="branch.country"
                                    :label="t('country')"
                                    @update:modelValue="changeCountry(i)"
                                    :location="branch"
                                />
                            </div>
                            <div
                                class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                            >
                                <SelectInput
                                    v-model="branch.city"
                                    :label="t('city')"
                                    :options="
                                        cities(i)?.map((opt) => ({
                                            label: opt.name,
                                            value: opt.name,
                                        }))
                                    "
                                />
                            </div>
                            <div
                                class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            >
                                <BaseInput
                                    v-model="branch.address"
                                    :label="t('address')"
                                />
                            </div>
                            <div
                                class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            >
                                <BaseInput
                                    v-model="branch.state"
                                    :label="t('state')"
                                />
                            </div>
                            <div
                                class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            >
                                <BaseInput
                                    v-model="branch.zip"
                                    :label="t('zip')"
                                />
                            </div>

                            <div
                                class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                            >
                                <BaseInput
                                    v-model="branch.mobile"
                                    :label="t('mobile')"
                                />
                            </div>
                            <div
                                class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12"
                            >
                                <BaseInput
                                    v-model="branch.phone"
                                    :label="t('phone')"
                                />
                            </div>

                            <div
                                class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12"
                            >
                                <BaseInput
                                    v-model="branch.email"
                                    :label="t('email')"
                                    type="email"
                                />
                            </div>

                            <div
                                class="col-lg-2 col-md-6 col-xs-12 q-px-md q-pb-sm"
                            >
                                <q-toggle
                                    keep-color
                                    v-model="branch.is_active"
                                    :trueValue="1"
                                    :falseValue="0"
                                    :label="t('is_active')"
                                    :disable="Boolean(branch.is_main)"
                                />
                            </div>

                            <div
                                class="col-lg-1 col-md-2 col-xs-12 text-center"
                            >
                                <RemoveBtn
                                    @click="
                                        () =>
                                            removeFrom(formData, 'branches', i)
                                    "
                                    class="q-mb-lg"
                                    :disabled="Boolean(branch.is_main)"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                        class="text-h6 text-center text-weight-bold q-pa-md"
                        v-if="!formData.branches.length"
                    >
                        {{ t("card.no_data", { key: t("card.branches") }) }}
                    </div>
                </CardSectionWithHeader>

                <!-- Logo -->
                <CardUpload
                    title="card.logo"
                    :form-data="formData"
                    :config="{
                        keyOfImages: 'logo',
                        label: 'choose_your_logo',
                    }"
                />

                <!-- Remarks -->
                <CardRemarks :form-data="formData" />
            </div>
        </q-form>
    </q-card>
</template>
