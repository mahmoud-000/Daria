<script setup>
import { computed, ref } from "vue";
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
import {
    genderTypes,
    containsLowercase,
    containsUppercase,
    containsNumber,
    containsSpecial,
} from "../../../utils/constraints";
import {
    BaseInput,
    CardSectionWithHeader,
    CardUpload,
    CardContacts,
    CardLocations,
    CardRemarks,
    DateInput,
    SelectInput,
} from "../../../components/import";

const { t } = useI18n();
const isPwd = ref(true);
const isPwdConf = ref(true);

const store = useStore();

await store.dispatch("auth/fetchProfileUser");
const formData = computed(() => store.getters["auth/getProfile"]);

const rules = computed(() => ({
    username: { required, minLength: minLength(8), maxLength: maxLength(100) },
    firstname: { minLength: minLength(3), maxLength: maxLength(50) },
    lastname: { minLength: minLength(3), maxLength: maxLength(50) },
    password: {
        requiredIfRef: requiredIf(!formData.value.id),
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
        requiredIfRef: requiredIf(!formData.value.id),
        sameAsPassword: helpers.withMessage(
            t("validations.sameAs", {
                property: "password",
                other: "password_confirmation",
            }),
            sameAs(formData.value.password)
        ),
    },
    email: { email, requiredIfRef: requiredIf(formData.value.send_notify) },
    gender: { required },
    date_of_birth: { required },
    date_of_joining: { required },
    is_active: { required },
}));
const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();
    if (!validate) {
        return;
    }

    await store.dispatch("auth/updateProfile", formData.value);
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
                            {{ t("links.profile") }}
                        </div>
                    </div>
                </div>

                <div
                    class="col-lg-6 col-md-12 col-xs-12 flex justify-center items-center"
                >
                    <div class="col-12">
                        <q-btn
                            :label="t('links.dashboard')"
                            color="negative"
                            :to="{ name: 'dashboard' }"
                            class="q-mr-md"
                        />
                        <q-btn
                            :label="t('action.save')"
                            type="submit"
                            color="positive"
                            @click="onSubmit"
                            v-permission="[`edit-user-profile`]"
                        />
                    </div>
                </div>
            </div>
        </q-card-section>
        <q-form @submit="onSubmit">
            <div class="row q-col-gutter-md q-mt-xs">
                <!-- Image -->
                <CardUpload :form-data="formData" />

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
                                v-model="formData.firstname"
                                :label="t('firstname')"
                                :error="$v.firstname.$error"
                                @input="() => $v.firstname.$touch()"
                                @blur="() => $v.firstname.$touch()"
                                :errors="$v.firstname.$errors"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData.lastname"
                                :label="t('lastname')"
                                :error="$v.lastname.$error"
                                @input="() => $v.lastname.$touch()"
                                @blur="() => $v.lastname.$touch()"
                                :errors="$v.lastname.$errors"
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
                            <SelectInput
                                v-model="formData.gender"
                                :label="t('gender')"
                                :options="genderTypes"
                                :error="$v.gender.$error"
                                :errors="$v.gender.$errors"
                                @input="() => $v.gender.$touch()"
                                @blur="() => $v.gender.$touch()"
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
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <DateInput
                                v-model="formData.date_of_birth"
                                :label="t('date_of_birth')"
                                :error="$v.date_of_birth.$error"
                                :errors="$v.date_of_birth.$errors"
                                @input="() => $v.date_of_birth.$touch()"
                                @blur="() => $v.date_of_birth.$touch()"
                            />
                        </div>
                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <DateInput
                                v-model="formData.date_of_joining"
                                :label="t('date_of_joining')"
                                :error="$v.date_of_joining.$error"
                                :errors="$v.date_of_joining.$errors"
                                @input="() => $v.date_of_joining.$touch()"
                                @blur="() => $v.date_of_joining.$touch()"
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
