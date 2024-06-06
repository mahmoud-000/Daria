<script setup>
import { toRefs } from "vue";
import { Dark } from "quasar";
import {
    CardSectionWithHeader,
    FileUpload
} from "../../import";

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: () => 'card.image',
    },
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
    config: {
        type: Object,
        required: false,
        default: () => {},
    },
});
const { title, formData, config } = toRefs(props);
</script>

<template>
    <CardSectionWithHeader :title="title">
        <div
            class="row justify-center q-pt-lg"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
            style="min-height: 165px"
        >
            <div class="col-lg-9 col-md-12 col-xs-12 q-px-md q-pb-sm">
                <FileUpload
                    :form-data="formData"
                    :config="config"
                />
            </div>
        </div>
    </CardSectionWithHeader>
</template>
