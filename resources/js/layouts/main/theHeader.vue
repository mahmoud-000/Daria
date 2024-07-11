<script setup>
import { computed } from "vue";
import { Dark } from "quasar";
import { useStore } from "vuex";
import { TheSwitcherLang, TheFullScreen } from "../../components/import";

const store = useStore();

const loginUser = computed(() => store.getters["auth/LoginUser"]);

const logout = () => store.dispatch("auth/logoutAction");
const getTextClass = computed(() => store.getters["getTextClass"]);
</script>
<template>
    <q-header
        flat
        height-hint="64"
        :class="{
            'bg-white text-dark': !Dark.isActive,
            'bg-dark': Dark.isActive,
        }"
    >
        <q-toolbar style="height: 64px">
            <slot />
            
            <q-space />
            <div class="q-gutter-sm row items-center no-wrap">
                <the-switcher-lang />
                <the-full-screen />

                <q-btn dense flat no-wrap>
                    <q-avatar size="26px">
                        <q-img :src="loginUser && loginUser['avatar']['url']" />
                    </q-avatar>
                    
                    <q-icon name="fa-solid fa-caret-down" size="16px" />

                    <q-menu
                        auto-close
                        fit
                        transition-show="jump-down"
                        transition-hide="jump-up"
                    >
                        <q-list dense style="min-width: 100px">
                            <q-item
                                v-permission="[`show-user-profile`]"
                                clickable
                                v-ripple
                                :to="{ name: 'user-profile' }"
                                class="text-white"
                                :active-class="`${getTextClass} bg-primary`"
                            >
                                <q-item-section>
                                    {{ $t("links.profile") }}
                                </q-item-section>
                            </q-item>
                          
                            <q-item clickable v-ripple @click="logout">
                                <q-item-section>
                                    {{ $t("auth.logout") }}
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-menu>
                </q-btn>
            </div>
        </q-toolbar>
    </q-header>
</template>
