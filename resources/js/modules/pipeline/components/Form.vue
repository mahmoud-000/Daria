<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
import { helpers } from "@vuelidate/validators";
import { required } from "../../../utils/i18n-validators";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { useRouter, useRoute } from "vue-router";

import {
    CardHeader,
    CardSectionWithHeader,
    CardRemarks,
    BaseInput,
    SelectInput,
    AddBtn,
    RemoveBtn,
} from "../../../components/import";

import { stage, moduleNames } from "../../../utils/constraints";
import { addTo, removeFrom } from "../../../utils/helpers";

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
    name: { required },
    app_name: { required },
    is_active: { required },
    stages: {
        $each: helpers.forEach({
            name: { required },
            complete: { required },
            color: { required },
        }),
    },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("pipeline/updatePipeline", formData.value);
        router.push({ name: "pipeline.list" });
    }
    if (!route.params.id) {
        await store.dispatch("pipeline/createPipeline", formData.value);
        router.push({ name: "pipeline.list" });
    }
};

const sortStages = () => {
    formData.value.stages.sort((a, b) => a.complete - b.complete);
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
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <BaseInput
                                v-model="formData[`name`]"
                                :label="t(`name`)"
                                :error="$v[`name`].$error"
                                @input="() => $v[`name`].$touch()"
                                @blur="() => $v[`name`].$touch()"
                                :errors="$v[`name`].$errors"
                            />
                        </div>

                        <div
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.app_name"
                                :label="t('app_name')"
                                :options="moduleNames"
                                :error="$v.app_name.$error"
                                :errors="$v.app_name.$errors"
                                @input="() => $v.app_name.$touch()"
                                @blur="() => $v.app_name.$touch()"
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

                <!-- Stages -->
                <CardSectionWithHeader title="card.stages">
                    <template #btn>
                        <AddBtn
                            @click="() => addTo(formData, 'stages', stage)"
                        />
                    </template>

                    <div
                        v-for="(stage, i) in formData?.stages"
                        :key="i"
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                    >
                        <div class="row justify-between items-center q-py-lg">
                            <div
                                class="q-px-md q-pb-sm col-lg-4 col-md-4 col-xs-12"
                            >
                                <BaseInput
                                    :label="t('name')"
                                    v-model="stage.name"
                                    :errors="
                                        $v.stages.$each.$response.$errors[i]
                                            .name
                                    "
                                    :error="
                                        Boolean(
                                            $v.stages.$each.$response.$errors[i]
                                                .name.length
                                        )
                                    "
                                />
                            </div>
                            <div
                                class="q-px-md q-pb-sm col-lg-2 col-md-2 col-xs-12"
                            >
                                <BaseInput
                                    v-model.number="stage.complete"
                                    :label="t('complete')"
                                    type="number"
                                    :readonly="stage.is_default"
                                    :errors="
                                        $v.stages.$each.$response.$errors[i]
                                            .complete
                                    "
                                    :error="
                                        Boolean(
                                            $v.stages.$each.$response.$errors[i]
                                                .complete.length
                                        )
                                    "
                                    @blur="sortStages"
                                />
                            </div>
                            <div
                                class="q-px-md q-pb-sm col-lg-2 col-md-2 col-xs-12"
                            >
                                <BaseInput
                                    :label="t('color')"
                                     :rules="['anyColor']"
                                    v-model="stage.color"
                                    readonly
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
                                                    v-model="stage.color"
                                                />
                                            </q-popup-proxy>
                                        </q-icon>
                                    </template>
                                </BaseInput>
                            </div>
                            <div
                                class="col-lg-2 col-md-2 col-xs-12 q-px-md q-pb-sm"
                            >
                                <q-toggle
                                    keep-color
                                    v-model="stage.is_active"
                                    :trueValue="1"
                                    :falseValue="0"
                                    :label="t('is_active')"
                                    :disable="stage.is_default"
                                />
                            </div>
                            <div
                                class="col-lg-2 col-md-2 col-xs-12 text-center"
                            >
                                <RemoveBtn
                                    @click="
                                        () => removeFrom(formData, 'stages', i)
                                    "
                                    class="q-mb-lg"
                                    :disabled="stage.is_default"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
                        class="text-h6 text-center text-weight-bold q-pa-md"
                        v-if="!formData.stages.length"
                    >
                        {{ t("card.no_data", { key: t("card.stages") }) }}
                    </div>
                </CardSectionWithHeader>

                <!-- Remarks -->
                <CardRemarks :form-data="formData" />
            </div>
        </q-form>
    </q-card>
</template>
