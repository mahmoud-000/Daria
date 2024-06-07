<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
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
    CompanyInput,
    CountryInput,
    SelectInput
} from "../../../components/import";
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
    company_id: { required },
    is_active: { required },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("branch/updateBranch", formData.value);
        router.push({ name: "branch.list" });
    }
    if (!route.params.id) {
        await store.dispatch("branch/createBranch", formData.value);
        router.push({ name: "branch.list" });
    }
};

const changeCountry = () => {
    formData.value.city = null;
};

const cities = () =>
    countries.find(
        (country) => country?.countryName === formData.value.country
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
                            class="col-lg-4 col-md-6 col-xs-12 q-px-md q-pb-sm"
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
                            class="col-lg-4 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <CompanyInput
                                v-model="formData.company_id"
                                :error="$v.company_id.$error"
                                @input="() => $v.company_id.$touch()"
                                @blur="() => $v.company_id.$touch()"
                                :errors="$v.company_id.$errors"
                            />
                        </div>

                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                v-model="formData.email"
                                :label="t('email')"
                                type="email"
                            />
                        </div>

                        <div
                            class="col-lg-3 col-md-3 col-xs-12 q-px-md q-pb-sm"
                        >
                            <CountryInput
                                v-model="formData.country"
                                :label="t('country')"
                                @update:modelValue="changeCountry()"
                                :location="formData"
                            />
                        </div>
                        <div
                            class="col-lg-3 col-md-3 col-xs-12 q-px-md q-pb-sm"
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
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.address"
                                :label="t('address')"
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
                            <BaseInput v-model="formData.zip" :label="t('zip')" />
                        </div>

                        <div
                            class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                v-model="formData.mobile"
                                :label="t('mobile')"
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
