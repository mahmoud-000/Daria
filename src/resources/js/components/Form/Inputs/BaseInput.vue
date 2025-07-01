<script setup>
import { toRefs, computed } from "vue";
import { useStore } from "vuex";

const props = defineProps({
    inputModel: {
        type: [String, Number],
        default: "",
    },
    errors: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const { errors } = toRefs(props);
const store = useStore();
const colorBasedOnMode = computed(() => store.getters["colorBasedOnMode"]);
</script>

<template>
    <q-input
        dense
        outlined
        rounded
        bottom-slots
        lazy-rules
        v-bind="$attrs"
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        autocomplete="off"
        hide-bottom-space
        hide-hint
        :color="colorBasedOnMode"
    >
        <template #error v-if="errors.length">
            <div :class="$q.dark.isActive ? 'text-red-3' : 'text-negative'">
                {{ errors.length ? errors[0].$message : "" }}
            </div>
        </template>

        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </q-input>
</template>
