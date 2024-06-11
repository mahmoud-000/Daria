<script setup>
import { computed, ref, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, minLength, maxLength } from "../../../utils/i18n-validators";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { Dark } from "quasar";
import { useRouter, useRoute } from "vue-router";

import {
    BaseInput,
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
} from "../../../components/import";

const { t } = useI18n();
const router = useRouter();
const route = useRoute();

const props = defineProps({
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
});
const { formData } = toRefs(props);

const rules = computed(() => ({
    name: { required, minLength: minLength(4), maxLength: maxLength(100) },
    is_active: { required },
    permissions: { required },

}));
const $v = useVuelidate(rules, formData);
const store = useStore();

await store.dispatch("permission/fetchOptions");
const permissions = computed(() => store.getters["permission/getOptions"]);

const onSubmit = async () => {
    const validate = await $v.value.$validate();
    if (!validate) {
        return;
    }
    if (route.params.id) {
        await store.dispatch("role/updateRole", formData.value);
        router.push({ name: "role.list" });
    }
    if (!route.params.id) {
        await store.dispatch("role/createRole", formData.value);
        router.push({ name: "role.list" });
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
            :reference="formData?.name"
            :item-id="formData?.id"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        />
        <q-form @submit="onSubmit">
            <div class="row q-col-gutter-md q-mt-xs">
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
                                v-model="formData.name"
                                :label="t('name')"
                                :error="$v.name.$error"
                                @input="() => $v.name.$touch()"
                                @blur="() => $v.name.$touch()"
                                :errors="$v.name.$errors"
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

                <CardSectionWithHeader title="card.access_rights">
                    <div
                        class="row justify-center q-pt-lg"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div
                            class="col-lg-12 col-md-12 col-xs-12 q-px-md q-pb-md"
                        >
                            <q-tree
                                class="col-12 col-sm-6"
                                :nodes="permissions"
                                node-key="name"
                                label-key="name"
                                tick-strategy="leaf"
                                v-model:ticked="formData.permissions"
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
