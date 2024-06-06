<script setup>
import { ref, computed, watch, toRefs } from "vue";
import { Dark } from "quasar";
import { CardSectionWithHeader, SelectInput } from "../../import";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: () => "card.access_rights",
    },
    formData: {
        type: Object,
        required: true,
        default: () => {},
    },
});
const { title, formData } = toRefs(props);

const { t } = useI18n();
const store = useStore();

Promise.all([
    store.dispatch("role/fetchOptions"),
    store.dispatch("permission/fetchOptions"),
]);

const roles = computed(() => store.getters["role/getOptions"]);
const permissions = computed(() => store.getters["permission/getOptions"]);

const perOptions = ref(permissions.value);
const extraPers = Object.assign([], [...formData.value.permissions]);

watch(
    () => formData.value.roles,
    (val) => {
        if (val.length) {
            formData.value.permissions = [...extraPers];
            const permissionsInRole = roles.value.find((role) =>
                val.includes(role.id)
            )?.permissions;

            perOptions.value = permissions.value
                .map((per) => ({
                    ...per,
                    disabled: false,
                    children: per.children.map((child) => ({
                        ...child,
                        disabled: false,
                    })),
                }))
                .filter((pe) => {
                    pe.children.filter((p) => {
                        if (
                            permissionsInRole &&
                            permissionsInRole.includes(p.name)
                        ) {
                            p.disabled = true;
                            formData.value.permissions.push(p.name);
                        }
                    });
                    return pe;
                });
        }
    },
    { immediate: true }
);

const options = ref(roles.value);

const filterFn = (val, update) => {
    if (val === "") {
        update(() => {
            options.value = roles.value;

            // here you have access to "ref" which
            // is the Vue reference of the QSelect
        });
        return;
    }

    update(() => {
        const needle = val.toLowerCase();
        options.value = roles.value.filter(
            (v) => v.name.toLowerCase().indexOf(needle) > -1
        );
    });
};
</script>

<template>
    <CardSectionWithHeader :title="title">
        <div
            class="row justify-center q-pt-lg"
            :class="!Dark.isActive ? 'bg-white' : 'bg-dark'"
        >
            <div class="col-lg-12 col-md-12 col-xs-12 q-px-md q-pb-md">
                <SelectInput
                    v-model="formData.roles"
                    clearable
                    :label="t('roles')"
                    :options="
                        options.map((opt) => ({
                            label: opt.name,
                            value: opt.id,
                        }))
                    "
                    multiple
                    use-input
                    input-debounce="0"
                    @filter="filterFn"
                />
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 q-px-md q-pb-md">
                <q-tree
                    class="col-12 col-sm-6"
                    :nodes="perOptions"
                    node-key="name"
                    label-key="name"
                    tick-strategy="leaf"
                    v-model:ticked="formData.permissions"
                />
            </div>
        </div>
    </CardSectionWithHeader>
</template>
