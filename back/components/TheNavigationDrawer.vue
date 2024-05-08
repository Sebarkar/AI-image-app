<script setup lang="ts">
const localePath = useLocalePath()

const {t} = useI18n()
const rail = ref(true)
const temporary = ref(false)

const state = useStateStore();

const singleItems = [
    {
        icon: 'mdi-lightbulb-on',
        title: 'site.index',
        link: '/',
    },
    {
        icon: 'mdi-lightbulb-on',
        title: 'site.users',
        link: '/users',
    },
]

import {useDisplay} from 'vuetify';

const {lgAndUp} = useDisplay();

watch(
    lgAndUp, (val) => {
        temporary.value = !val;
    },
    {
        immediate: true,
    }
);

</script>

<template>
    <v-navigation-drawer
        v-if="!lgAndUp"
        v-model="state.drawer"
        border="0"
        elevation="0"
        temporary
        disable-route-watcher
    >
        <div class="py-5 px-7 overflow-hidden w-100 flex-shrink-0">
            <img src="/images/logo-full.jpg" alt="" class="logo_icon">
        </div>
        <v-list
            shaped
            nav
            class="pa-4"
        >
            <v-list-item
                v-for="item in singleItems"
                rounded="lg"
                :key="item.title"
                density="comfortable"
                @click="state.changeDrawer(false)"
                class="py-1"
                :to="{ path: localePath(item.link) }"
            >
                <template #prepend>
                    <v-icon :icon="item.icon" size="x-small" class="px-3"/>
                </template>
                <v-list-item-title class="text-subtitle-1" v-text="t(item.title)"/>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
    <v-navigation-drawer
        v-else
        border="0"
        elevation="0"
        expand-on-hover
        :temporary="temporary"
        disable-route-watcher
        :rail="!state.drawer"
        rail-width="72"
    >
        <div class="py-5 px-7 overflow-hidden w-100 flex-shrink-0">
            <img src="/images/logo-full.jpg" alt="" class="logo_icon">
        </div>
        <v-list
            shaped
            nav
            class="px-4 py-2"
        >
            <v-list-item
                v-for="item in singleItems"
                rounded="lg"
                density="comfortable"
                class="py-1"
                :to="{ path: localePath(item.link) }"
            >
                <template #prepend>
                    <v-icon :icon="item.icon" size="x-small" class="px-3"/>
                </template>
                <v-list-item-title class="text-subtitle-1" v-text="t(item.title)"/>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
