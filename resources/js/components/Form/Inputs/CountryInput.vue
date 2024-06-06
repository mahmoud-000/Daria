<script setup>
import { defineAsyncComponent, ref, toRefs, computed } from "vue";
import countries from "../../../utils/countries";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
const CountryFlag = defineAsyncComponent(() => import("vue-country-flag-next"));
import { SelectInput } from "../../import";

const props = defineProps({
    location: {
        type: Object,
        required: true,
        default: () => {},
    },
    inputModel: {
        type: String,
        default: () => "eg",
    },
});

const { location } = toRefs(props);

const options = ref(countries);

const filterCountries = (val, update) => {
    if (val === "") {
        update(() => {
            options.value = countries;
        });
        return;
    }
    update(() => {
        const needle = val.toLowerCase();
        options.value = countries.filter(
            (v) => v.countryName.toLowerCase().indexOf(needle) > -1
        );
    });
};

const { t } = useI18n();
const store = useStore();
const getTextColor = computed(() => store.getters["getTextColor"]);
</script>

<template>
    <SelectInput
        use-input
        input-debounce="0"
        option-value="countryName"
        option-label="countryName"
        :options="options"
        @update:modelValue="(value) => (inputModel = value)"
        @filter="filterCountries"
        v-bind="$attrs"
    >
        <template #option="scope">
            <q-item v-bind="scope.itemProps">
                <q-item-section avatar>
                    <CountryFlag
                        :country="scope.opt?.countryShortCode"
                        size="normal"
                    />
                </q-item-section>
                <q-item-section>
                    <q-item-label>
                        {{ scope.opt.countryName }}
                    </q-item-label>
                    <q-item-label caption>{{
                        scope.opt?.countryShortCode
                    }}</q-item-label>
                </q-item-section>
            </q-item>
        </template>

        <template #selected-item>
            <country-flag
                v-if="location.country"
                :country="
                    countries.find(
                        (country) => country.countryName === location.country
                    )?.countryShortCode
                "
                size="small"
            />
            <q-chip
                dense
                color="primary"
                :text-color="getTextColor"
                class="q-ma-none"
                v-if="location.country"
            >
                {{ location.country }}
            </q-chip>
        </template>
    </SelectInput>
</template>
