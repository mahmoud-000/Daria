<script setup>
import { computed, ref, reactive } from "vue";
import useVuelidate from "@vuelidate/core";
import {
    required,
    email,
    minLength,
    maxLength,
    sameAs,
    requiredIf,
} from "../../../utils/i18n-validators";
import { helpers } from "@vuelidate/validators";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";
import {
    containsLowercase,
    containsUppercase,
    containsNumber,
    containsSpecial,
} from "../../../utils/constraints";

import {
    BaseInput,
    CardUpload,
    CardSectionWithHeader,
    CardContacts,
    CardLocations,
    CardRemarks,
} from "../../../components/import";

const { t } = useI18n();
const router = useRouter();
const route = useRoute();
const isPwd = ref(true);
const isPwdConf = ref(true);

const formData = reactive({
    username: "",
    email: "",
    company_name: "",
    password: "",
    password_confirmation: "",
    contacts: [],
    locations: [],
    remarks: "",
});

const rules = computed(() => ({
    username: { required, minLength: minLength(8), maxLength: maxLength(100) },
    company_name: { minLength: minLength(3), maxLength: maxLength(50) },
    password: {
        requiredIfRef: requiredIf(!route.params.id),
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
    password_confirmation: {
        requiredIfRef: requiredIf(!route.params.id),
        sameAsPassword: helpers.withMessage(
            t("validations.sameAs", {
                property: "password",
                other: "password_confirmation",
            }),
            sameAs(formData.password)
        ),
    },
    email: { email, requiredIfRef: requiredIf(formData.send_notify) },
}));

const $v = useVuelidate(rules, formData);
const store = useStore();

const onSubmit = async () => {
    const validate = await $v.value.$validate();
    if (!validate) {
        return;
    }

    await store.dispatch("customer/register", formData);
    router.push({ name: "login" });
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
        <q-card-section :class="!$q.dark.isActive ? 'bg-white' : 'bg-dark'">
            <div class="row items-center justify-center">
                <div
                    class="col-lg-6 col-md-12 col-xs-12 flex justify-center items-center"
                >
                    <div class="col-12">
                        <div class="text-h5">
                            {{
                                t("action.register", {
                                    module: t(`links.customer`, 1),
                                })
                            }}
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
                            :to="{ name: 'login' }"
                            class="q-mr-md"
                        />
                        <q-btn
                            :label="t('action.save')"
                            type="submit"
                            color="positive"
                            @click="onSubmit"
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
                    :config="{ keyOfImages: 'logo' }"
                />
                <!-- General Informations -->
                <CardSectionWithHeader title="card.general_info">
                    <div
                        class="row justify-between q-py-lg"
                        :class="!$q.dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
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
                                v-model="$v.password_confirmation.$model"
                                :label="t('password_confirmation')"
                                :error="$v.password_confirmation.$error"
                                @input="() => $v.password_confirmation.$touch()"
                                @blur="() => $v.password_confirmation.$touch()"
                                :errors="$v.password_confirmation.$errors"
                                :type="isPwdConf ? 'password' : 'text'"
                            >
                                <template #append>
                                    <q-icon
                                        :name="
                                            isPwdConf
                                                ? 'visibility_off'
                                                : 'visibility'
                                        "
                                        class="cursor-pointer"
                                        @click="isPwdConf = !isPwdConf"
                                    />
                                </template>
                            </BaseInput>
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.company_name"
                                :label="t('company_name')"
                                :error="$v.company_name.$error"
                                @input="() => $v.company_name.$touch()"
                                @blur="() => $v.company_name.$touch()"
                                :errors="$v.company_name.$errors"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Contacts -->
                <CardContacts :form-data="formData" />

                <!-- Locations -->
                <CardLocations :form-data="formData" />

                <!-- Remarks -->
                <CardRemarks :form-data="formData" />
            </div>
        </q-form>
    </q-card>
</template>
