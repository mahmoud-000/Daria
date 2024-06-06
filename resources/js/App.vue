<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import { useStore } from "vuex";
import { Cookies } from "quasar";

import { LayoutBlank, LayoutContent, TheSpinner } from "./components/import";

const store = useStore();
store.commit("SET_MODE", Cookies.get("mode"), { root: true });
store.commit("SET_THEME", Cookies.get("theme"), { root: true });

const route = useRoute();
const layouts = {
    blank: LayoutBlank,
    content: LayoutContent,
};
const resolveLayout = computed(() => {
    if (!route.name) return;

    if (route.meta.layout === "blank") return layouts["blank"];

    return layouts["content"];
});
</script>
<template>
    <!-- mode="out-in" -->
    <component :is="resolveLayout">
        <Suspense>
            <template #default>
                <RouterView />
            </template>
            <template #fallback>
                <div class="fixed-center">
                    <the-spinner />
                </div>
            </template>
        </Suspense>
    </component>
</template>
