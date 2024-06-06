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

        <div v-if="formData?.contacts.length" :class="!Dark.isActive ? 'bg-white' : 'bg-dark'">
            <div v-for="(contact, i) in formData?.contacts" :key="i" class="q-py-md">
                <div class="row justify-between items-center">
                    <div class="q-px-md q-pb-sm col-lg-2 col-md-6 col-xs-12">
                        <BaseInput v-model="contact.name" :label="t('name')" />
                    </div>
                    <div class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12">
                        <BaseInput
                            :label="t('mobile')"
                            v-model="contact.mobile"
                        />
                    </div>
                    <div class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12">
                        <BaseInput
                            v-model="contact.phone"
                            :label="t('phone')"
                        />
                    </div>

                    <div class="q-px-md q-pb-sm col-lg-3 col-md-6 col-xs-12">
                        <BaseInput
                            v-model="contact.email"
                            :label="t('email')"
                            type="email"
                        />
                    </div>
                    <div class="col-lg-1 col-md-12 col-xs-12 flex flex-center">
                        <RemoveBtn
                            @click="() => removeFrom(formData, 'contacts', i)"
                        />
                    </div>
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
