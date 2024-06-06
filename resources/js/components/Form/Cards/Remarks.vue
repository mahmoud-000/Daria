<script setup>
import { toRefs } from "vue";
import { Dark } from "quasar";
import { CardSectionWithHeader, RemarksInput } from "../../import";

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: () => "card.remarks",
    },
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
});
const { title, formData } = toRefs(props);
</script>

<template>
    <CardSectionWithHeader :title="title">
        <div :class="!Dark.isActive ? 'bg-white' : 'bg-dark'">
            <div class="row items-center q-pt-lg">
                <div class="col-12 q-px-md q-pb-md">
                    <RemarksInput v-model="formData.remarks" />
                </div>
            </div>
        </div>
    </CardSectionWithHeader>
</template>
