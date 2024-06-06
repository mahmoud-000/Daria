import { RouterView } from 'vue-router';
<template>
    <q-layout>
        <q-page-container
            class="page-container"
            :class="{ 'bg-grey-4': !$q.dark.isActive }"
        >
            <q-page padding>
                <router-view v-slot="{ Component, route }">
                    <transition name="fade" mode="out-in" appear>
                        <div :key="route.name">
                            <component :is="Component" />
                        </div>
                    </transition>
                </router-view>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
