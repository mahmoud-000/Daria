<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import { useStore } from "vuex";
import useVuelidate from "@vuelidate/core";
import { required } from "../../../utils/i18n-validators";
import { useI18n } from "vue-i18n";
import { Quasar, Dark } from "quasar";
import { BaseInput } from "../../../components/import";
import { langList } from "../../../utils/boot_lang";

const formData = reactive({
    username: "admin",
    password: "Passwordsecret1@",
    remember: false,
    device: "web",
});

const isPwd = ref(true);
const rules = computed(() => ({
    username: { required },
    password: { required },
}));
const store = useStore();
const v = useVuelidate(rules, formData);
const submit = async () => {
    try {
        await store.dispatch("auth/loginAction", formData);
        await store.dispatch("locale/fetchLocales");
    } catch (error) {
        console.log(error);
    }
};

const SYSTEM_NAME = computed(() => store.getters["setting/getSystemName"]);

// const { locale } = useI18n({ useScope: "global" });

// onMounted(() => {
//     const langIso = locale.value === "en" ? "en-US" : locale.value;
//     try {
//         langList[`../../../node_modules/quasar/lang/${langIso}.mjs`]().then(
//             (lang) => {
//                 Quasar.lang.set(lang.default);
//                 store.dispatch("locale/setLocale", locale.value);
//             }
//         );
//     } catch (err) {
//         console.log(err);
//         // Requested Quasar Language Pack does not exist,
//         // let's not break the app, so catching error
//     }
// });
</script>

<template>
    <q-page
        :class="{ 'bg-grey-4': !Dark.isActive }"
        class="row justify-center items-center"
    >
        <q-card
            flat
            style="border-radius: 20px"
            class="col-lg-3 col-md-6 col-sm-8"
        >
            <q-card-section class="row no-wrap text-center">
                <div class="col text-h5 text-bold">
                    {{ $t("auth.login_message") }}

                    <span class="text-secondary text-bold">{{
                        SYSTEM_NAME
                    }}</span>
                </div>
            </q-card-section>
            <q-card-section>
                <q-form @submit="submit" class="q-gutter-md">
                    <BaseInput
                        v-model="formData.username"
                        :label="$t('username')"
                        :error="v.username.$error"
                        @input="() => v.username.$touch()"
                        @blur="() => v.username.$touch()"
                        :errors="v.username.$errors"
                    />

                    <BaseInput
                        v-model="formData.password"
                        :label="$t('password')"
                        :error="v.password.$error"
                        @input="() => v.password.$touch()"
                        @blur="() => v.password.$touch()"
                        :errors="v.password.$errors"
                        :type="isPwd ? 'password' : 'text'"
                    >
                        <template #append>
                            <q-icon
                                :name="isPwd ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"
                                class="cursor-pointer"
                                @click="isPwd = !isPwd"
                            />
                        </template>
                    </BaseInput>

                    <div class="row items-center justify-between">
                        <q-toggle
                            dense
                            v-model="formData.remember"
                            :label="$t('auth.remember')"
                            color="primary"
                            checked-icon="fa-solid fa-check"
                            unchecked-icon="fa-solid fa-xmark"
                        />

                        <router-link
                            :to="{ name: 'forget_password' }"
                            style="text-decoration: none"
                            class="text-primary"
                        >
                            {{ $t("auth.forget_password") }}
                        </router-link>
                    </div>

                    <div
                        class="row items-center justify-end no-wrap q-gutter-md"
                    >
                        <q-btn
                            :label="$t('auth.login')"
                            color="primary"
                            type="submit"
                        />
                    </div>
                </q-form>
            </q-card-section>
        </q-card>
    </q-page>
</template>
