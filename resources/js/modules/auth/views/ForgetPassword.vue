<script setup>
import { reactive, computed } from "vue";
import { useStore } from "vuex";
import useVuelidate from "@vuelidate/core";
import { required, email } from "../../../utils/i18n-validators";

import { BaseInput } from "../../../components/import";

const formData = reactive({
    email: "",
});

const rules = computed(() => ({
    email: { required, email },
}));

const store = useStore();

const $v = useVuelidate(rules, formData);

const submit = async () => {
    const result = await $v.value.$validate();
    if (result) {
        store.dispatch("auth/forgetPasswordAction", formData);
    }
};
</script>

<template>
    <q-page
        :class="{ 'bg-grey-4': !$q.dark.isActive }"
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

                    <span class="text-primary text-bold">{{
                        SYSTEM_NAME
                    }}</span>
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

                    <div
                        class="row items-center justify-end no-wrap q-gutter-md"
                    >
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
