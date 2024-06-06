<script setup>
import { computed } from "vue";
import { Dark } from "quasar";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";

const store = useStore();
const { t } = useI18n();

setTimeout(async () => {
    store.commit("ticket/SET_ACTIVE", true);
    await store.dispatch("ticket/fetchGroupByStatus", {});
}, 200);

const getGroupByStatus = computed(
    () => store.getters["ticket/getGroupByStatus"]
);

const staticts = computed(() => [
    {
        icon: "lock_open",
        count: getGroupByStatus.value.find((g) => g.status === 1)?.total ?? 0,
    },
    {
        icon: "recycling",
        count: getGroupByStatus.value.find((g) => g.status === 2)?.total ?? 0,
    },
    {
        icon: "check",
        count: getGroupByStatus.value.find((g) => g.status === 3)?.total ?? 0,
    },
]);
</script>

<template>
    <div class="row col-12 q-col-gutter-md justify-center q-mb-md">
        <div class="col-lg-12">
            <div class="row q-col-gutter-md justify-center">
                <div
                    class="col-12 col-lg-4 col-md-6 col-sm-6"
                    v-for="(statict, i) in staticts"
                    :key="i"
                >
                    <q-card
                        :class="{
                            'bg-white': !Dark.isActive,
                        }"
                        dark
                        flat
                    >
                        <q-card-section class="row justify-around items-center">
                            <q-icon
                                :color="Dark.isActive ? 'white' : 'primary'"
                                :name="statict.icon"
                                size="7em"
                            ></q-icon>
                            <q-badge rounded color="negative" ext-color="white">
                                <div class="text-h5 text-weight-bolder">
                                    {{ statict.count }}
                                    {{ t("links.ticket", 2) }}
                                </div>
                            </q-badge>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>
