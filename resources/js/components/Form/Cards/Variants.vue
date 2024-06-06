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
});
const { formData } = toRefs(props);

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
            <div class="row justify-between items-center q-pt-lg">
                <div class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12">
                    <BaseInput
                        rounded
                        outlined
                        :label="t('name')"
                        v-model="variant.name"
                    />
                </div>
                <div class="q-px-md q-pb-sm col-lg-2 col-md-6 col-xs-12">
                    <BaseInput
                        rounded
                        outlined
                        :label="t('code')"
                        v-model="variant.code"
                    />
                </div>
                <div class="q-px-md q-pb-sm col-lg-2 col-md-6 col-xs-12">
                    <BaseInput
                        rounded
                        outlined
                        :label="t('cost')"
                        v-model="variant.cost"
                    />
                </div>
                <div class="q-px-md q-pb-sm col-lg-2 col-md-6 col-xs-12">
                    <BaseInput
                        rounded
                        outlined
                        :label="t('price')"
                        v-model="variant.price"
                    />
                </div>
                <div class="q-px-md q-pb-sm col-lg-2 col-md-4 col-xs-12">
                    <BaseInput
                        :label="t('color')"
                        v-model="variant.color"
                        readonly
                    >
                        <template #append>
                            <q-icon name="colorize" class="cursor-pointer">
                                <q-popup-proxy
                                    cover
                                    transition-show="scale"
                                    transition-hide="scale"
                                >
                                    <q-color
                                        :model-value="hex"
                                        v-model="variant.color"
                                    />
                                </q-popup-proxy>
                            </q-icon>
                        </template>
                    </BaseInput>
                </div>
                <div class="col-lg-1 col-md-2 col-xs-12 text-center">
                    <RemoveBtn
                        @click="() => removeFrom(formData, 'variants', i)"
                        class="q-mb-lg"
                        />
                        <!-- :disabled="variant.default" -->
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
