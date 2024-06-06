<script setup>
import { ref, computed, toRefs } from "vue";
import currencies from "../../../utils/currencies";
import { useStore } from "vuex";
import { SelectInput } from "../../import";

const props = defineProps({
    inputModel: {
        type: String,
        default: "",
        efault: () => "USD",
    },
    errors: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const { errors } = toRefs(props);
const store = useStore();
const getTextColor = computed(() => store.getters["getTextColor"]);

const options = ref(currencies);

const filterFn = (val, update) => {
    if (val === "") {
        update(() => {
            options.value = currencies;

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
        });
        return;
    }

    update(() => {
        const needle = val.toLowerCase();
        options.value = currencies.filter(
            (v) =>
                v.name.toLowerCase().indexOf(needle) > -1 ||
                v.code.toLowerCase().indexOf(needle) > -1
        );
    });
};
</script>

<template>
    <SelectInput
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        :options="
            options?.map((opt) => ({
                label: opt.name,
                value: opt.code,
            }))
        "
        v-bind="$attrs"
        @filter="filterFn"
        use-input
        input-debounce="0"
        :errors="errors"
    />
</template>
