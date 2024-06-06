<script setup>
import { ref, computed } from "vue";
import {
    TheBreadcrumbs,
    TheSpinner,
    TheHeader,
    TheLeftSidebar,
} from "../components/import";
import { Dark } from "quasar";
import { useStore } from "vuex";
import { useI18n } from "vue-i18n";

const leftDrawerOpen = ref(true);
const miniState = ref(false);

const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value;
};

const { t } = useI18n();
const store = useStore();
const SYSTEM_NAME = computed(() => store.getters["setting/getSystemName"]);

const drawerClick = (e) => {
    if (miniState.value) {
        miniState.value = false;
        store.commit("SET_DRAWER_MINI", false, { root: true });
        // e.stopPropagation();
    }
};
const miniBtnClick = () => {
    if (!miniState.value) {
        miniState.value = true;
        store.commit("SET_DRAWER_MINI", true, { root: true });
    }
};

const getTextColor = computed(() => store.getters["getTextColor"]);
</script>

<template>
    <q-layout view="hHh Lpr lff">
        <the-header>
            <div
                v-if="leftDrawerOpen && !miniState"
                class="flex flex-center q-mb-sm q-mr-sm"
                style="width: 180px; height: 100px"
            >
                <q-toolbar-title shrink class="row items-center no-wrap">
                    <span class="q-ml-sm text-weight-bold">
                        {{ SYSTEM_NAME }}
                    </span>
                </q-toolbar-title>
            </div>
            <q-btn
                flat
                dense
                round
                @click="toggleLeftDrawer"
                aria-label="Menu"
                icon="menu"
            />
        </the-header>

        <q-page-container
            class="page-container"
            :style="!Dark.isActive ? 'background-color: #F2F2F2' : ''"
        >
            <Suspense>
                <template #default>
                    <q-page padding>
                        <the-breadcrumbs />

                        <router-view v-slot="{ Component, route }">
                            <transition
                                appear
                                enter-active-class="fadeIn"
                                leave-active-class="fadeOut"
                            >
                                <div :key="route.name">
                                    <component :is="Component" />
                                </div>
                            </transition>
                        </router-view>
                    </q-page>
                </template>
                <template #fallback>
                    <the-spinner />
                </template>
            </Suspense>
            <Suspense>
                <template #default>
                    <!-- :mini="miniState"
                        @mouseover="miniState = false"
                        @mouseout="miniState = true"
                        show-if-above
                        mini-to-overlay
                        bordered -->
                    <q-drawer
                        v-model="leftDrawerOpen"
                        :width="200"
                        :breakpoint="500"
                        :mini="miniState"
                        @click.capture="drawerClick"
                        show-if-above
                        bordered
                        persistent
                    >
                        <the-left-sidebar />
                        <div
                            class="q-mini-drawer-hide absolute"
                            style="top: 15px; right: -17px"
                        >
                            <q-btn
                                dense
                                round
                                unelevated
                                color="primary"
                                :text-color="getTextColor"
                                icon="chevron_left"
                                @click="() => miniBtnClick()"
                            />
                        </div>
                    </q-drawer>
                </template>
                <template #fallback>
                    <the-spinner />
                </template>
            </Suspense>
        </q-page-container>
    </q-layout>
</template>
