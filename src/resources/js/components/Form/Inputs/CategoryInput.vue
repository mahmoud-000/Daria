<script setup>
import { ref, toRefs, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";
import { SelectInputWithMedia } from "../../import";
import { useFilterLazy } from "../../../composables/filterLazy";

const props = defineProps({
    inputModel: {
        type: String,
        default: () => "",
    },
    errors: {
        type: Array,
        required: false,
        default: () => [],
    },
    otherQuery: {
        type: Object,
        required: false,
        default: () => {},
    },
});

const { otherQuery } = toRefs(props);
const { t } = useI18n();
const store = useStore();

const { handleFilter, options, loading } = useFilterLazy("category");
const filterFn = async (val, update) =>
    handleFilter(val, update, ["name"], otherQuery.value);

const colorBasedOnMode = computed(() => store.getters["colorBasedOnMode"]);
</script>

<template>
    <SelectInputWithMedia
        :options="
            options?.map((opt) => ({
                label: opt.name,
                value: opt.id,
                img: opt.logo.url,
            }))
        "
        use-input
        input-debounce="0"
        @filter="filterFn"
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        :loading="loading"
        v-bind="$attrs"
        :label="t('category_id')"
        transition-show="jump-up"
        transition-hide="jump-up"
        behavior="menu"
        :color="colorBasedOnMode"
        :errors="errors"
    />
</template>
