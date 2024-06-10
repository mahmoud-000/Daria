<script setup>
import { toRefs } from "vue";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import {
    AddBtn,
    RemoveBtn,
    BaseInput,
    CardSectionWithHeader,
} from "../../import";
import { variant } from "../../../utils/constraints";
import { addTo, removeFrom } from "../../../utils/helpers";

const props = defineProps({
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
    v: {
        type: Object,
        required: true,
        default: () => {},
    },
});
const { formData, v } = toRefs(props);

const { t } = useI18n();
</script>

<template>
    <CardSectionWithHeader title="card.variants">
        <template #btn>
            <AddBtn @click="() => addTo(formData, 'variants', variant)" />
        </template>

        <div
            v-for="(variant, i) in formData?.variants"
            :key="i"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        >
            <div class="row justify-between items-center q-py-lg q-px-md">
                <div class="q-px-sm q-pb-sm col-lg-2 col-md-6 col-xs-12">
                    <BaseInput
                        :label="t('name')"
                        v-model="variant.name"
                        :errors="v.variants.$each.$response.$errors[i].name"
                        :error="
                            Boolean(
                                v.variants.$each.$response.$errors[i].name
                                    .length
                            )
                        "
                    />
                </div>
                <div class="q-px-sm q-pb-sm col-lg-2 col-md-6 col-xs-12">
                    <BaseInput
                        :label="t('sku')"
                        v-model="variant.sku"
                        :errors="v.variants.$each.$response.$errors[i].sku"
                        :error="
                            Boolean(
                                v.variants.$each.$response.$errors[i].sku.length
                            )
                        "
                    />
                </div>
                <div class="q-px-sm q-pb-sm col-lg-2 col-md-6 col-xs-12">
                    <BaseInput
                        :label="t('code')"
                        v-model="variant.code"
                        :errors="v.variants.$each.$response.$errors[i].code"
                        :error="
                            Boolean(
                                v.variants.$each.$response.$errors[i].code.length
                            )
                        "
                    />
                </div>
                <div class="q-px-sm q-pb-sm col-lg-1 col-md-6 col-xs-12">
                    <BaseInput
                        :label="t('cost')"
                        v-model="variant.cost"
                        :error="
                            Boolean(
                                v.variants.$each.$response.$errors[i].cost
                                    .length
                            )
                        "
                    />
                </div>
                <div class="q-px-sm q-pb-sm col-lg-1 col-md-6 col-xs-12">
                    <BaseInput
                        :label="t('price')"
                        v-model="variant.price"
                        :error="
                            Boolean(
                                v.variants.$each.$response.$errors[i].price
                                    .length
                            )
                        "
                    />
                </div>
                
                <div class="col-lg-2 col-md-6 col-xs-12 q-px-sm q-pb-sm">
                    <q-toggle
                        keep-color
                        v-model="variant.is_active"
                        :trueValue="1"
                        :falseValue="0"
                        :label="t('is_active')"
                        :error="
                            Boolean(
                                v.variants.$each.$response.$errors[i].is_active
                                    .length
                            )
                        "
                    />
                </div>
                <div class="col-lg-1 col-md-2 col-xs-12 text-center">
                    <RemoveBtn
                        @click="() => removeFrom(formData, 'variants', i)"
                    />
                </div>
            </div>
        </div>

        <div
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
            class="text-h6 text-center text-weight-bold q-pa-md"
            v-if="!formData.variants.length"
        >
            {{ t("card.no_data", { key: t("card.variants") }) }}
        </div>
    </CardSectionWithHeader>
</template>
