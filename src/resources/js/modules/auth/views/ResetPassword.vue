<script setup>
import { defineAsyncComponent, ref, reactive, computed } from "vue";
import { useStore } from "vuex";
import useVuelidate from "@vuelidate/core";
import { required, email, sameAs } from "../../../utils/i18n-validators";
import { useRoute, useRouter } from "vue-router";
const theSwitcherLang = defineAsyncComponent(() =>
    import("../../../layouts/main/theSwitcherLang.vue")
);
const BaseInput = defineAsyncComponent(() =>
    import("../../../components/Form/Inputs/BaseInput.vue")
);

const formData = reactive({
    email: "",
    password: "",
    password_confirmation: "",
});

const rules = computed(() => ({
    email: { required, email },
    password: { required },
    password_confirmation: {
        required,
        sameAsPassword: sameAs(formData.password),
    },
}));

const isPwd = ref(true);
const isPwdConf = ref(true);

const store = useStore();
const route = useRoute();
const router = useRouter();

const $v = useVuelidate(rules, formData);
const submit = async () => {
    const result = await $v.value.$validate();
    const token = route.query.token;
    if (result) {
        try {
            store.dispatch("auth/resetPasswordAction", { ...formData, token });
            router.push({ name: "login" });
        } catch (error) {}
    }
};
</script>

<template>
    <q-page class="flex flex-center">
        <div
            id="particles-js"
            :class="$q.dark.isActive ? 'dark_gradient' : 'normal_gradient'"
        ></div>
        <div class="flex justify-between absolute-top-right q-pr-lg">
            <the-switcher-lang />
        </div>
        <q-card
            class="login-form"
            v-bind:style="$q.screen.lt.sm ? { width: '80%' } : { width: '30%' }"
        >
            <q-card-section class="relative-position">
                <q-avatar
                    size="74px"
                    class="absolute-top-right"
                    style="transform: translateY(-50%)"
                >
                    <q-img src="https://cdn.quasar.dev/img/boy-avatar.png" />
                </q-avatar>
                <div class="row no-wrap items-center">
                    <div class="col text-h6 ellipsis">
                        {{ $t("auth.reset_password_message") }}
                    </div>
                </div>
            </q-card-section>
            <q-card-section>
                <q-form @submit="submit" class="q-gutter-md">
                    <BaseInput
                        v-model="formData.email"
                        :label="$t('email')"
                        :error="$v.email.$error"
                        @input="() => $v.email.$touch()"
                        @blur="() => $v.email.$touch()"
                        :errors="$v.email.$errors"
                        type="email"
                    />

                    <BaseInput
                        v-model="formData.password"
                        :label="$t('password')"
                        :error="$v.password.$error"
                        @input="() => $v.password.$touch()"
                        @blur="() => $v.password.$touch()"
                        :errors="$v.password.$errors"
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

                    <BaseInput
                        v-model="formData.password_confirmation"
                        :label="$t('password_confirmation')"
                        :error="$v.password_confirmation.$error"
                        @input="() => $v.password_confirmation.$touch()"
                        @blur="() => $v.password_confirmation.$touch()"
                        :errors="$v.password_confirmation.$errors"
                        :type="isPwdConf ? 'password' : 'text'"
                    >
                        <template #append>
                            <q-icon
                                :name="
                                    isPwdConf ? 'visibility_off' : 'visibility'
                                "
                                class="cursor-pointer"
                                @click="isPwdConf = !isPwdConf"
                            />
                        </template>
                    </BaseInput>

                    <div class="row items-center justify-between q-gutter-md">
                        <q-btn
                            :label="$t('auth.send_reset_email')"
                            color="primary"
                            type="submit"
                        />
                        <q-btn
                            :label="$t('auth.login')"
                            color="primary"
                            :to="{ name: 'login' }"
                        />
                    </div>
                </q-form>
            </q-card-section>
        </q-card>
    </q-page>
</template>