<script setup>
import { computed, toRefs } from "vue";
import useVuelidate from "@vuelidate/core";
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
} from "../../../components/import";

const { t } = useI18n();
const route = useRoute();
const router = useRouter();
const store = useStore();

const pipelines = computed(() => store.getters["pipeline/getOptions"]);

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
    complete: { required },
    color: { required },
    pipeline_id: { required },
    is_active: { required },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("stage/updateStage", formData.value);
        router.push({ name: "stage.list" });
    }
    if (!route.params.id) {
        await store.dispatch("stage/createStage", formData.value);
        router.push({ name: "stage.list" });
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
                            class="q-px-md q-pb-sm col-lg-6 col-md-6 col-xs-12"
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
                            class="col-lg-6 col-md-6 col-xs-12 q-px-md q-pb-sm"
                        >
                            <SelectInput
                                v-model="formData.pipeline_id"
                                :label="t('pipeline_id')"
                                :options="
                                    pipelines.map((opt) => ({
                                        label: opt.name,
                                        value: opt.id,
                                    }))
                                "
                                :error="$v.pipeline_id.$error"
                                :errors="$v.pipeline_id.$errors"
                                @input="() => $v.pipeline_id.$touch()"
                                @blur="() => $v.pipeline_id.$touch()"
                                :readonly="formData.is_default"
                            />
                        </div>

                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-4 col-xs-12"
                        >
                            <BaseInput
                                v-model.number="formData.complete"
                                :label="t('complete')"
                                type="number"
                                :error="$v.complete.$error"
                                @input="() => $v.complete.$touch()"
                                @blur="() => $v.complete.$touch()"
                                :errors="$v.complete.$errors"
                                :readonly="formData.is_default"
                            />
                        </div>
                        <div
                            class="q-px-md q-pb-sm col-lg-4 col-md-4 col-xs-12"
                        >
                            <BaseInput
                                :label="t('color')"
                                v-model="formData.color"
                                readonly
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
                            class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm"
                        >
                            <q-toggle
                                keep-color
                                v-model="formData.is_active"
                                :trueValue="1"
                                :falseValue="0"
                                :label="t('is_active')"
                                :disable="formData.is_default"
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
