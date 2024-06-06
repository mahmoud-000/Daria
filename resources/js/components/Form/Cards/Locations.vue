<script setup>
import { toRefs } from "vue";
import { Dark } from "quasar";
import { useI18n } from "vue-i18n";
import {
    AddBtn,
    RemoveBtn,
    BaseInput,
    SelectInput,
    CountryInput,
    CardSectionWithHeader,
} from "../../import";
import countries from "../../../utils/countries";
import { location } from "../../../utils/constraints";
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

const changeCountry = (i) => {
    formData.value.locations[i].city = null;
};

const cities = (i) =>
    countries.find(
        (country) =>
            country?.countryName === formData.value.locations[i].country
    )?.regions;
</script>

<template>
    <CardSectionWithHeader title="card.locations">
        <template #btn>
            <AddBtn @click="() => addTo(formData, 'locations', location)" />
        </template>

        <div
            v-if="formData?.locations.length"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        >
            <div
                v-for="(location, i) in formData?.locations"
                :key="i"
                class="q-py-md"
            >
                <div class="row items-center">
                    <div class="q-px-md q-pb-sm col-lg-4 col-md-4 col-xs-12">
                        <BaseInput v-model="location.name" :label="t('name')" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm">
                        <CountryInput
                            v-model="location.country"
                            :label="t('country')"
                            @update:modelValue="changeCountry(i)"
                            :location="location"
                        />
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 q-px-md q-pb-sm">
                        <SelectInput
                            v-model="location.city"
                            :label="t('city')"
                            :options="
                                cities(i)?.map((opt) => ({
                                    label: opt.name,
                                    value: opt.name,
                                }))
                            "
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput
                            v-model="location.first_address"
                            :label="t('first_address')"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput
                            v-model="location.second_address"
                            :label="t('second_address')"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput
                            v-model="location.state"
                            :label="t('state')"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12 q-px-md q-pb-sm">
                        <BaseInput v-model="location.zip" :label="t('zip')" />
                    </div>
                    <div
                        class="col-lg-12 col-md-12 col-xs-12 flex flex-center text-center"
                    >
                        <RemoveBtn
                            @click="() => removeFrom(formData, 'locations', i)"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
            class="text-h6 text-center text-weight-bold q-pa-md"
            v-if="!formData.locations.length"
        >
            {{ t("card.no_data", { key: t("card.locations") }) }}
        </div>
    </CardSectionWithHeader>
</template>
