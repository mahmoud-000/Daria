<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

defineProps({
    inputModel: {
        type: String,
        default: () => '',
    },
    columns: {
        type: Array,
        required: true,
        default: () => [],
    },
});

const store = useStore();
const getTextColor = computed(() => store.getters["getTextColor"]);
const colorBasedOnMode = computed(() => store.getters["colorBasedOnMode"]);
</script>

<template>
    <q-select
        multiple
        outlined
        rounded
        dense
        options-dense
        :display-value="$q.lang.table.columns"
        emit-value
        map-options
        :options="columns"
        option-value="name"
        options-cover
        style="min-width: 100px"
        class="q-ml-md"
        transition-show="jump-up"
        transition-hide="jump-up"
        behavior="menu"
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        :options-selected-class="`text-${getTextColor} bg-primary`"
        hide-bottom-space
        hide-hint
        :color="colorBasedOnMode"

    />
</template>
