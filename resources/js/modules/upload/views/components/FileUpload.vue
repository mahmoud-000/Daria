<script setup>
import { ref, reactive, toRefs } from "vue";
import vueFilePond, { setOptions } from "vue-filepond";

import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";

const FilePond = vueFilePond(
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
);

const { t } = useI18n();
const props = defineProps({
    formData: {
        type: Object,
        required: true,
    },
    config: {
        type: Object,
        required: false,
        default: () => ({}),
    },
});

const { formData, config } = toRefs(props);

const keyOfImages = ref(config.value.keyOfImages ?? 'avatar')
const maxFiles = ref(config.value.maxFiles ?? 1)
const allowMultiple = ref(config.value.allowMultiple ?? false)
const instantUpload = ref(config.value.instantUpload ?? true)
const allowReorder = ref(config.value.allowReorder ?? false)
const acceptedTypes = ref(config.value.acceptedTypes ?? 'image/jpeg, image/png')
const imagePreviewHeight = ref(config.value.imagePreviewHeight ?? '100%')
const label = ref(config.value.label ?? 'choose_your_image')

const server = reactive({
    url: "/api/uploads",
    restore: (source, load) => {
        if (store) {
            fetch(source).then((res) =>
                res.blob().then((myBlob) => load(myBlob))
            );
        }
    },
});

const myFiles = reactive([]);

const route = useRoute();
const store = useStore();

const handleFilePondInit = () => {
    setOptions({
        server: {
            process: async (
                fieldName,
                file,
                metadata,
                load,
                error,
                progress,
                abort,
                transfer,
                options
            ) => {
                const data = new FormData();
                data.append("file", file, file.name);
                data.append("folder", keyOfImages.value);

                await store.dispatch("upload/upload", data);

                let fileUploaded = await store.state.upload.file;

                load(fileUploaded.filename);

                if (allowMultiple.value) {
                    formData.value[keyOfImages.value].push(fileUploaded);
                } else {
                    formData.value[keyOfImages.value] = fileUploaded;
                }
            },
            revert: (uniqueFileId, load, error) => {
                if (allowMultiple.value) {
                    formData.value[keyOfImages.value].shift(uniqueFileId);
                } else {
                    formData.value[keyOfImages.value].value = { url: "" };
                }

                if (
                    [undefined].includes(formData.value[keyOfImages.value].fake)
                ) {
                    let deletedImage = myFiles.find(
                        (file) => file.source === uniqueFileId
                    );
                    store.dispatch("upload/destroy", {
                        collection: keyOfImages.value,
                        filename: uniqueFileId,
                        // modelId: formData.value.id,
                        id: deletedImage?.file.uuid,
                    });
                }

                error("oh my goodness");
                load();
            },
        },
    });
};

const loadImages = async () => {
    if (formData.value) {
        if (route.params.id) {
            if (allowMultiple.value) {
                formData.value[keyOfImages.value].forEach((image) => {
                    myFiles.push({
                        source: image.original_url,
                        options: {
                            type: "limbo",
                        },
                        file: {
                            uuid: image.uuid,
                            name: image.filename,
                            size: image.size,
                            type: image.mime_type,
                            order_column: image.order_column,
                        },
                    });
                });
            } else {
                if (formData.value[keyOfImages.value]) {
                    myFiles.push({
                        source: formData.value[keyOfImages.value]?.original_url,
                        options: {
                            type: "limbo",
                        },
                        file: {
                            uuid: formData.value[keyOfImages.value]?.uuid,
                            name: formData.value[keyOfImages.value]?.filename,
                            size: formData.value[keyOfImages.value]?.size,
                            type: formData.value[keyOfImages.value]?.mime_type,
                        },
                    });
                }
            }
        } else {
            formData.value[keyOfImages.value] = [];
        }
    }
};

const reorderFiles = async (files) => {
    let order = 1;
    files.forEach((file) => {
        let img = formData.value[keyOfImages.value].find(
            (image) => image.filename === file.filename
        );
        img.order_column = order++;
    });
    await store.dispatch("upload/reorder", formData.value[keyOfImages.value]);
};

await loadImages();
</script>

<template>
    <file-pond
        ref="pond"
        :label-idle="t(label)"
        :accepted-file-types="acceptedTypes"
        :files="myFiles"
        :allow-multiple="allowMultiple"
        :instantUpload="instantUpload"
        :allowReorder="allowReorder"
        :dropValidation="true"
        :dropOnPage="true"
        :server="server"
        @init="handleFilePondInit"
        @reorderfiles="reorderFiles"
        credits="false"
        :imagePreviewHeight="imagePreviewHeight"
        :maxFiles="maxFiles"
    />
</template>

<style scopped>
.filepond--panel-root {
    background-color: var(--q-primary);
}
.filepond--drop-label {
    color: #fff;
}

.filepond--image-preview-overlay svg {
    display: none;
}

[data-filepond-item-state="processing-complete"] .filepond--item-panel {
    background-color: var(--q-primary);
}
</style>
