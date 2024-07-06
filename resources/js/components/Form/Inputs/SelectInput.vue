<script setup>
import { computed, toRefs } from "vue";
import { useI18n } from "vue-i18n";
import { useStore } from "vuex";

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
});

const { t } = useI18n();
const { errors } = toRefs(props);
const store = useStore();
const getTextColor = computed(() => store.getters["getTextColor"]);
const colorBasedOnMode = computed(() => store.getters["colorBasedOnMode"]);
</script>

<template>
    <q-select
        dense
        outlined
        rounded
        options-cover
        options-dense
        emit-value
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        v-bind="$attrs"
        transition-show="jump-up"
        transition-hide="jump-up"
        behavior="menu"
        map-options
        :options-selected-class="`text-${getTextColor} bg-primary`"
        hide-bottom-space
        hide-hint
        :color="colorBasedOnMode"
    >
        <template #no-option>
            <q-item>
                <q-item-section class="text-italic text-grey">
                    {{ t("messages.no_options") }}
                </q-item-section>
            </q-item>
        </template>

        <template #error>
            <div :class="$q.dark.isActive ? 'text-red-3' : 'text-negative'">
                {{ errors.length ? errors[0].$message : "" }}
            </div>
        </template>

        <template #selected-item="scope">
            <q-chip
                :tabindex="scope.tabindex"
                color="primary"
                :text-color="getTextColor"
                class="q-ma-none"
            >
                {{ scope.opt.label }}
            </q-chip>
        </template>
        
        <template v-for="(_, name) in $slots" #[name]="slotData">
            <slot :name="name" v-bind="slotData || {}" />
        </template>
    </q-select>
</template>
