<script setup lang="ts">
const guest = useGuestStore();
const auth = useAuthStore();
const modals = useModalsStore();
const listener = useListenerStore();
const route = useRoute();

const {t} = useI18n()

const head = useLocaleHead({
    addDirAttribute: true,
    identifierAttribute: 'id',
    addSeoAttributes: true
})

const title = computed(() => t('layouts.title', {title: t(route.meta.title ?? 'TBD')}))
watch(() => auth.user, (user) => {
    if (user) {
        listener.register(user);
    }
});

onMounted(() => {
    if (auth.user) {
        listener.register(auth.user);
    }
});

</script>

<template>
    <div>
        <Html :lang="head.htmlAttrs.lang" :dir="head.htmlAttrs.dir">
        <Head>
            <Title>{{ title }}</Title>
            <template v-for="link in head.link" :key="link.id">
                <Link :id="link.id" :rel="link.rel" :href="link.href" :hreflang="link.hreflang"/>
            </template>
            <template v-for="meta in head.meta" :key="meta.id">
                <Meta :id="meta.id" :property="meta.property" :content="meta.content"/>
            </template>
        </Head>
        <Body>
        <TheTopPanel v-if="route.meta.layout === 'default'"/>
        <UNotifications/>
        <UContainer>
            <NuxtLayout>
                <NuxtPage/>
            </NuxtLayout>
        </UContainer>
        <ModalsAuth :value="modals.isModalOpened('auth')"></ModalsAuth>
        <USlideovers />
        </Body>
        </Html>
    </div>
</template>
