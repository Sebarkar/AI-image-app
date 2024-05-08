<script setup lang="ts">
const {t, locale, locales} = useI18n()

const router = useRouter()
const state = useStateStore()
const snackbarStore = useSnackbarStore();
const switchLocalePath = useSwitchLocalePath()

const changeLanguage = (val) => {
    router.push(switchLocalePath(val))
}
const mm = null
</script>

<template>
    <v-app id="inspire">
        <TheNavigationDrawer/>
        <v-app-bar :elevation="0">
            <v-app-bar-nav-icon @click="state.changeDrawer()"></v-app-bar-nav-icon>

            <v-app-bar-title></v-app-bar-title>
            <v-menu
                transition="slide-y-transition"
            >
                <template v-slot:activator="{ props }">
                    <v-btn
                        color="primary"
                        variant="tonal"
                        class="mr-5"
                        size="small"
                        height="36px"
                        v-bind="props"
                        icon="$language"
                    />
                </template>
                <v-card
                    class="mx-auto"
                    elevation="2"
                    width="200px"
                >
                    <v-toolbar color="secondary">
                        <v-toolbar-title class="text-caption">{{ $t('site.languages') }}</v-toolbar-title>
                    </v-toolbar>
                    <v-list>
                        <v-list-item
                            v-for="(local, i) in locales"
                            :key="i"
                            :variant="locale === local.code ? 'tonal' : 'text'"
                            :base-color="locale === local.code ? 'primary' : ''"
                            @click-once="changeLanguage(local.code)"
                        >
                            <v-list-item-title>{{ local.title }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-card>
            </v-menu>
        </v-app-bar>
        <v-main class="mx-2 mr-lg-3 ml-lg-0">
            <v-locale-provider :locale="locale">
                <div class="content_wrapper">
                    <div class="page_wrapper">
                        <v-container fluid>
                            <v-snackbar
                                timer
                                close-on-content-click
                                position="static"
                                :key="id"
                                v-for="(snackbar, id) in snackbarStore.snackbars"
                                :color="snackbar.color"
                                @update:modelValue="snackbarStore.remove(snackbar)"
                                :model-value="true"
                                :timeout="snackbar.timeout ? snackbar.timeout : '5000'"
                            >
                                {{ snackbar.text }}
                            </v-snackbar>
                            <slot/>
                        </v-container>

                    </div>
                </div>
            </v-locale-provider>
        </v-main>
    </v-app>
</template>

<style scoped>

</style>
