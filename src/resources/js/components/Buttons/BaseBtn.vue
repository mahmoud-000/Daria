<script setup>
import { toRefs } from "vue";

const props = defineProps({
    color: {
        type: String,
        required: false,
        default: () => "primary",
    },
    toolbar: {
        type: String,
        required: true,
        default: () => "",
    },
});
const { color, toolbar } = toRefs(props);
</script>

<template>
    <q-btn dense round unelevated size="sm" :color="color" v-bind="$attrs">
        <q-tooltip dark :class="`bg-${color}`" v-if="toolbar">
            {{ toolbar }}
        </q-tooltip>
    </q-btn>
</template>
