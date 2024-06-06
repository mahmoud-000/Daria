<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import useVuelidate from "@vuelidate/core";
import {
    email,
    minLength,
    maxLength,
    requiredIf,
    url,
    required,
} from "../../../utils/i18n-validators";
import { helpers } from "@vuelidate/validators";
import { useI18n } from "vue-i18n";
import { Dark } from "quasar";
import { useStore } from "vuex";
import {
    drivers,
    containsLowercase,
    containsUppercase,
    containsNumber,
    containsSpecial,
} from "../../../utils/constraints";
import {
    BaseInput,
    CardSectionWithHeader,
    CardUpload,
    SelectInput,
    CurrencyInput,
} from "../../../components/import";

const { t } = useI18n();
const isPwd = ref(true);

const formData = reactive({
    email: "",
    system_name: "",
    company_name: "",
    company_phone: "",
    company_address: "",
    currency: null,
    driver: "",
    host: "",
    port: null,
    encryption: false,
    username: "",
    password: "",
    sender_name: "",
    sender_email: "",
    vat_registration_number: "",
    system_logo: undefined,
    facebook_url: "",
    twitter_url: "",
});

const rules = computed(() => ({
    email: { email },
    host: { requiredIfRef: requiredIf(formData.driver) },
    port: { requiredIfRef: requiredIf(formData.driver) },
    encryption: { requiredIfRef: requiredIf(formData.driver) },
    username: { requiredIfRef: requiredIf(formData.driver) },
    password: {
        requiredIfRef: requiredIf(formData.driver),
        containsUppercase: helpers.withMessage(
            t("validations.contains_uppercase", { property: "password" }),
            containsUppercase
        ),
        containsLowercase: helpers.withMessage(
            t("validations.contains_lowercase", { property: "password" }),
            containsLowercase
        ),
        containsNumber: helpers.withMessage(
            t("validations.contains_number", { property: "password" }),
            containsNumber
        ),
        containsSpecial: helpers.withMessage(
            t("validations.contains_special", { property: "password" }),
            containsSpecial
        ),
        minLength: minLength(8),
        maxLength: maxLength(100),
    },
    sender_name: { requiredIfRef: requiredIf(formData.driver) },
    sender_email: { requiredIfRef: requiredIf(formData.driver), email },
    facebook_url: { url },
    twitter_url: { url },
    currency: { required },
}));

const $v = useVuelidate(rules, formData);
const store = useStore();

const locale = computed(() => store.getters["locale/getLocale"]);

const onSubmit = async () => {
    let data = [];
    Object.keys(formData).forEach((key) => {
        data.push({
            key: key,
            value: formData[key] ?? null,
        });
    });
    const validate = await $v.value.$validate();
    if (!validate) {
        return;
    }

    await store.dispatch("setting/createOrUpdate", data);
};
await store.dispatch("setting/fetchSystemSettings");
const settings = computed(() => store.getters["setting/getSystemSettings"]);
const DEFAULT_SYSTEM_LOGO = computed(
    () => store.getters["setting/getDefaultSystemLogo"]
);

const getSettingValue = async (key) => {
    return settings.value.find((setting) => setting.key === key)?.value;
};
onMounted(async () => {
    Object.keys(formData).forEach(async (key) => {
        formData[key] = (await getSettingValue(key)) || formData[key];
        if (key === "encryption") {
            formData[key] = formData[key] === "0" ? false : true;
        }

        if (key === "system_logo" && formData[key] === undefined) {
            formData[key] = DEFAULT_SYSTEM_LOGO.value;
        }
    });
});

const clearDriver = () => {
    formData.host = "";
    formData.port = null;
    formData.encryption = false;
    formData.username = "";
    formData.password = "";
    formData.sender_name = "";
    formData.sender_email = "";
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
        <q-card-section :class="!Dark.isActive ? 'bg-white' : 'bg-dark'">
            <div class="row items-center justify-center">
                <div
                    class="col-lg-6 col-md-12 col-xs-12 flex justify-center items-center"
                >
                    <div class="col-12">
                        <div class="text-h5">
                            {{ t("card.system_settings") }}
                        </div>
                    </div>
                </div>

                <div
                    class="col-lg-6 col-md-12 col-xs-12 flex justify-center items-center"
                >
                    <div class="col-12">
                        <q-btn
                            :label="t('action.cancel')"
                            color="negative"
                            :to="{ name: 'setting.list' }"
                            class="q-mr-md"
                            v-permission="['list-system-settings']"
                        />
                        <q-btn
                            :label="t('action.save')"
                            type="submit"
                            color="positive"
                            @click="onSubmit"
                            v-permission="['edit-system-settings']"
                        />
                    </div>
                </div>
            </div>
        </q-card-section>
        <q-form @submit="onSubmit">
            <div class="row q-col-gutter-md q-mt-xs">
                <!-- Image -->
                <CardUpload
                    :form-data="formData"
                    title="card.system_logo"
                    :config="{ keyOfImages: 'system_logo' }"
                />
                <!-- General Informations -->
                <CardSectionWithHeader title="card.general_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.system_name"
                                :label="t('system_name')"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.email"
                                :label="t('email')"
                                type="email"
                                :error="$v.email.$error"
                                @input="() => $v.email.$touch()"
                                @blur="() => $v.email.$touch()"
                                :errors="$v.email.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.company_name"
                                :label="t('company_name')"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.company_phone"
                                :label="t('company_phone')"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.company_address"
                                :label="t('company_address')"
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
                            <CurrencyInput
                                v-model="formData.currency"
                                :label="t('currency')"
                                :error="$v.currency.$error"
                                :errors="$v.currency.$errors"
                                @input="() => $v.currency.$touch()"
                                @blur="() => $v.currency.$touch()"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Links -->
                <CardSectionWithHeader title="card.related_links">
                    <div
                        class="row justify-between items-center q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div class="q-px-md q-pb-sm col-lg-3 col-md-3 col-xs-3">
                            {{ t(`facebook_url`) }}
                        </div>
                        <div class="q-px-md q-pb-sm col-lg-7 col-md-7 col-xs-7">
                            <BaseInput
                                v-model="formData.facebook_url"
                                :label="t(`facebook_url`)"
                                :error="$v.facebook_url.$error"
                                @input="() => $v.facebook_url.$touch()"
                                @blur="() => $v.facebook_url.$touch()"
                                :errors="$v.facebook_url.$errors"
                            />
                        </div>
                    </div>
                    <div
                        class="row justify-between items-center q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div class="q-px-md q-pb-sm col-lg-3 col-md-3 col-xs-3">
                            {{ t(`twitter_url`) }}
                        </div>
                        <div class="q-px-md q-pb-sm col-lg-7 col-md-7 col-xs-7">
                            <BaseInput
                                v-model="formData.twitter_url"
                                :label="t(`twitter_url`)"
                                :error="$v.twitter_url.$error"
                                @input="() => $v.twitter_url.$touch()"
                                @blur="() => $v.twitter_url.$touch()"
                                :errors="$v.twitter_url.$errors"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Email Driver Informations -->
                <CardSectionWithHeader title="card.email_driver_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.driver"
                                :label="t('driver')"
                                :options="drivers"
                                clearable
                                @clear="clearDriver"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.host"
                                :label="t('host')"
                                :error="$v.host.$error"
                                @input="() => $v.host.$touch()"
                                @blur="() => $v.host.$touch()"
                                :errors="$v.host.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.port"
                                :label="t('port')"
                                :error="$v.port.$error"
                                @input="() => $v.port.$touch()"
                                @blur="() => $v.port.$touch()"
                                :errors="$v.port.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <q-toggle
                                keep-color
                                v-model="formData.encryption"
                                :label="t('encryption')"
                                :error="$v.encryption.$error"
                                @input="() => $v.encryption.$touch()"
                                @blur="() => $v.encryption.$touch()"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.username"
                                :label="t('username')"
                                :error="$v.username.$error"
                                @input="() => $v.username.$touch()"
                                @blur="() => $v.username.$touch()"
                                :errors="$v.username.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="$v.password.$model"
                                :label="t('password')"
                                :error="$v.password.$error"
                                @input="() => $v.password.$touch()"
                                @blur="() => $v.password.$touch()"
                                :errors="$v.password.$errors"
                                :type="isPwd ? 'password' : 'text'"
                            >
                                <template #append>
                                    <q-icon
                                        :name="
                                            isPwd
                                                ? 'visibility_off'
                                                : 'visibility'
                                        "
                                        class="cursor-pointer"
                                        @click="isPwd = !isPwd"
                                    />
                                </template>
                            </BaseInput>
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.sender_name"
                                :label="t('sender_name')"
                                :error="$v.sender_name.$error"
                                @input="() => $v.sender_name.$touch()"
                                @blur="() => $v.sender_name.$touch()"
                                :errors="$v.sender_name.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.sender_email"
                                :label="t('sender_email')"
                                type="email"
                                :error="$v.sender_email.$error"
                                @input="() => $v.sender_email.$touch()"
                                @blur="() => $v.sender_email.$touch()"
                                :errors="$v.sender_email.$errors"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>
            </div>
        </q-form>
    </q-card>
</template>
