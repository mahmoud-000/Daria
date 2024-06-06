<script setup>
import { toRefs } from "vue";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import { AddBtn, RemoveBtn, BaseInput, CardSectionWithHeader } from "../../import";
import { contact } from "../../../utils/constraints";
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
    <CardSectionWithHeader title="card.contacts">
        <template #btn>
            <AddBtn @click="() => addTo(formData, 'contacts', contact)" />
        </template>

        <div
            v-for="(contact, i) in formData?.contacts"
            :key="i"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        >
            <div class="row justify-between items-center q-pt-lg">
                <div class="q-px-md q-pb-sm col-lg-6 col-md-6 col-xs-12">
                    <BaseInput
                        rounded
                        outlined
                        :label="t('mobile')"
                        v-model="contact.mobile"
                    />
                </div>
                <div class="q-px-md q-pb-sm col-lg-6 col-md-6 col-xs-12">
                    <BaseInput v-model="contact.phone" :label="t('phone')" />
                </div>
                <div class="q-px-md q-pb-sm col-lg-6 col-md-6 col-xs-12">
                    <BaseInput
                        v-model="contact.email"
                        :label="t('email')"
                        type="email"
                    />
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 text-center">
                    <RemoveBtn
                        @click="() => removeFrom(formData, 'contacts', i)"
                        class="q-mb-lg"
                    />
                </div>
            </div>
        </div>

        <div
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
            class="text-h6 text-center text-weight-bold q-pa-md"
            v-if="!formData.contacts.length"
        >
            {{ t("card.no_data", { key: t("card.contacts") }) }}
        </div>
    </CardSectionWithHeader>
</template>
