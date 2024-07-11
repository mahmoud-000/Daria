<script setup>
defineProps({
    inputModel: {
        type: String,
        default: () => new Date().toISOString().slice(0, 10),
    },
    errors: {
        type: Array,
        required: false,
        default: () => [],
    },
    readonlyDate: {
        type: Boolean,
        required: false,
        default: false,
    },
});
</script>
<template>
    <q-input
        dense
        hide-hint
        hide-bottom-space
        outlined
        rounded
        :modelValue="inputModel"
        @update:modelValue="(value) => (inputModel = value)"
        bottom-slots
        lazy-rules
        readonly
        v-bind="$attrs"
    >
        <template #append>
            <q-icon name="fa-solid fa-calendar-days" class="cursor-pointer">
                <q-popup-proxy
                    cover
                    transition-show="scale"
                    transition-hide="scale"
                >
                    <q-date
                        :modelValue="inputModel"
                        @update:modelValue="(value) => (inputModel = value)"
                        mask="YYYY-MM-DD"
                        v-bind="$attrs"
                        :readonly="readonlyDate"
                    >
                        <div class="row items-center justify-end">
                            <q-btn
                                v-close-popup
                                :label="$t('action.save')"
                                color="primary"
                            />
                        </div>
                    </q-date>
                </q-popup-proxy>
            </q-icon>
        </template>
        <template #error>
            <div :class="$q.dark.isActive ? 'text-red-3' : 'text-negative'">
                {{ errors.length ? errors[0].$message : "" }}
            </div>
        </template>
    </q-input>
</template>
