<script setup lang="ts">
const core = useCoreStore()
const auth = useAuthStore()
const {locale} = useI18n()
const router = useRouter()
const localePath = useLocalePath()

core.$patch({language: locale.value})

watch(locale, (val) => {
    core.$patch({language: val})
})

if (!auth.isAuthorized) {
    router.push(localePath('/auth/sign-in'))
}

</script>

<template>
    <NuxtLayout>
        <NuxtPage/>
    </NuxtLayout>
</template>
