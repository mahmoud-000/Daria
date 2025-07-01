<script setup>
import { computed } from "vue";
import { Dark } from "quasar";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import {
    routeChildren,
    userNameCapitalize,
    userAvatar,
} from "../../utils/helpers";
import { useI18n } from "vue-i18n";

const { t } = useI18n();
const route = useRoute();
const store = useStore();

const getModuleName = (metaTitle) => {
    const [moduleName, moduleAction] = metaTitle.split(".");
    return moduleName;
};

const getModuleTitle = (metaTitle) => {
    const [moduleName, moduleAction] = metaTitle.split(".");
    return t(`links.${moduleName}`, 2);
};

const isActiveClass = computed(() => store.getters.isActiveClass(route.name));
const getDrawerMini = computed(() => store.getters.getDrawerMini);

const navigation = computed(() =>
    routeChildren().filter((r) =>
        [
            "people.list",
            "organization.list",
            "hrm.list",
            "item.list",
            //"variant.list",
            "invoice.list",
            "setting.list",
        ].includes(r.name)
    )
);
</script>
<template>
    <!-- drawer content -->
    <q-scroll-area
        class="fit"
        :class="{
            'bg-white': !Dark.isActive,
            'bg-dark': Dark.isActive,
        }"
        :horizontal-thumb-style="{ opacity: 0 }"
    >
        <q-list padding>
            <q-item v-ripple>
                <q-item-section v-if="!getDrawerMini" class="flex-center">
                    <q-avatar size="56px" class="q-mb-md q-ma-auto">
                        <q-img :src="userAvatar()" />
                    </q-avatar>
                    <div class="text-weight-bold text-center">
                        {{ userNameCapitalize() }}
                    </div>
                    <q-badge
                        outline
                        color="positive"
                        :label="t('card.online')"
                    />
                </q-item-section>
                <q-item-section avatar v-else class="flex-center">
                    <q-avatar size="40px" class="q-ma-auto">
                        <q-img :src="userAvatar()" />
                    </q-avatar>
                    <q-badge
                        outline
                        color="positive"
                        :label="t('card.online')"
                    />
                </q-item-section>
            </q-item>

            <!-- <q-separator spaced /> -->

            <q-item
                :class="[isActiveClass['dashboard']]"
                clickable
                v-ripple
                exact
                :to="{ name: 'dashboard' }"
            >
                <q-item-section avatar>
                    <q-icon name="fa-solid fa-gauge-high" />
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{ $t(`links.dashboard`, 2) }}</q-item-label>
                </q-item-section>
            </q-item>
            <template v-for="nav in navigation" :key="nav.name">
                <q-item
                    v-permission="[`${nav.meta.permissions}`]"
                    :class="[isActiveClass[getModuleName(nav.meta.title)]]"
                    clickable
                    v-ripple
                    exact
                    :to="{ name: nav.name }"
                >
                    <q-item-section avatar>
                        <q-icon :name="nav.meta.icon" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{
                            getModuleTitle(nav.meta.title)
                        }}</q-item-label>
                    </q-item-section>
                </q-item>
                <q-separator :key="'sep' + index" v-if="nav.separator" />
            </template>
        </q-list>
    </q-scroll-area>
</template>
