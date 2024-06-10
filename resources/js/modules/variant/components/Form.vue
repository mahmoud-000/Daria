<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, minLength, maxLength, minValue } from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";

import {
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    BaseInput,
    ItemInput
} from "../../../components/import";
import { getSystemCurrencySymbol } from "../../../utils/helpers";

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
    name: { required, minLength: minLength(3), maxLength: maxLength(100) },
    cost: { required, minValue: minValue(0) },
    price: { required, minValue: minValue(0) },
    code: { required, minLength: minLength(8) },
    color: { required },
    is_active: { required },
    item_id: { required },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("variant/updateVariant", formData.value);
        router.push({ name: "variant.list" });
    }
    if (!route.params.id) {
        await store.dispatch("variant/createVariant", formData.value);
        router.push({ name: "variant.list" });
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
                            <ItemInput
                                v-model="formData.item_id"
                                :error="$v.item_id.$error"
                                @input="() => $v.item_id.$touch()"
                                @blur="() => $v.item_id.$touch()"
                                :errors="$v.item_id.$errors"
                            />
                        </div>
                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                :label="t('name')"
                                v-model="formData.name"
                                :error="$v.name.$error"
                                @input="() => $v.name.$touch()"
                                @blur="() => $v.name.$touch()"
                                :errors="$v.name.$errors"
                            />
                        </div>
                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                :label="t('code')"
                                v-model="formData.code"
                                :error="$v.code.$error"
                                @input="() => $v.code.$touch()"
                                @blur="() => $v.code.$touch()"
                                :errors="$v.code.$errors"
                            />
                        </div>
                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                :label="t('cost')"
                                v-model="formData.cost"
                                :error="$v.cost.$error"
                                @input="() => $v.cost.$touch()"
                                @blur="() => $v.cost.$touch()"
                                :errors="$v.cost.$errors"
                                :prefix="getSystemCurrencySymbol"
                            />
                        </div>
                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                :label="t('price')"
                                v-model="formData.price"
                                :error="$v.price.$error"
                                @input="() => $v.price.$touch()"
                                @blur="() => $v.price.$touch()"
                                :errors="$v.price.$errors"
                                :prefix="getSystemCurrencySymbol"
                            />
                        </div>
                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-6 col-xs-12"
                        >
                            <BaseInput
                                :label="t('color')"
                                v-model="formData.color"
                                readonly
                                 :rules="['anyColor']"
                                :error="$v.color.$error"
                                @input="() => $v.color.$touch()"
                                @blur="() => $v.color.$touch()"
                                :errors="$v.color.$errors"
                            >
                                <template #append>
                                    <q-icon
                                        name="colorize"
                                        class="cursor-pointer"
                                    >
                                        <q-popup-proxy
                                            cover
                                            transition-show="scale"
                                            transition-hide="scale"
                                        >
                                            <q-color
                                                :model-value="hex"
                                                v-model="formData.color"
                                            />
                                        </q-popup-proxy>
                                    </q-icon>
                                </template>
                            </BaseInput>
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
                                :errors="$v.is_active.$errors"
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
