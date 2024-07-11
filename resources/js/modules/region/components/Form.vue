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
    CardUpload,
    BaseInput,
} from "../../../components/import";

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
    is_active: { required },
}));

const $v = useVuelidate(rules, formData);

const onSubmit = async () => {
    const validate = await $v.value.$validate();

    if (!validate) {
        return;
    }

    if (route.params.id) {
        await store.dispatch("region/updateRegion", formData.value);
        router.push({ name: "region.list" });
    }
    if (!route.params.id) {
        await store.dispatch("region/createRegion", formData.value);
        router.push({ name: "region.list" });
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
                            <q-toggle
                                keep-color
                                v-model="formData.is_active"
                                :trueValue="1"
                                :falseValue="0"
                                checked-icon="fa-solid fa-check"
                            unchecked-icon="fa-solid fa-xmark"
                                :label="t('is_active')"
                                :error="$v.is_active.$error"
                                @input="() => $v.is_active.$touch()"
                                @blur="() => $v.is_active.$touch()"
                            />
                        </div>
                    </div>
                </CardSectionWithHeader>

                <!-- Logo -->
                <CardUpload
                    title="card.logo"
                    :form-data="formData"
                    :config="{
                        keyOfImages: 'logo',
                        label: 'choose_your_logo',
                    }"
                />

                <!-- Remarks -->
                <CardRemarks :form-data="formData" />
            </div>
        </q-form>
    </q-card>
</template>
