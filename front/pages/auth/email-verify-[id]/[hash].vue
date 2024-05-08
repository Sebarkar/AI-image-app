<script setup lang="ts">
definePageMeta({
    layout: false,
    middleware: ['sanctum:auth'],
})

const route = useRoute();

const {data, pending, error} = await useLazyAsyncData(
    'email-verification', () => useApiFetch(`auth/email/verify/${route.params.id}/${route.params.hash}`, {
        method: 'GET'
    }),
)

const close = () => {
    window.close()
}

</script>

<template>
    <div class="relative h-screen w-full">
        <div class="absolute inset-center flex flex-center flex-col">
            <div class="mb-20">
                <h1 class="font-bold mb-20 text-center">{{ $t('auth.text.Email verification') }}</h1>
                <UAlert
                    v-if="!error"
                    class="text-start"
                    :title="$t('actions.text.Success')"
                    :description="$t('auth.text.Your email verified success')"
                    icon="success"
                    color="success"
                />
                <UAlert
                    v-else
                    class="text-start"
                    :title="$t('actions.text.Error')"
                    :description="$t('auth.text.error in verified process')"
                    icon="error"
                    color="error"
                />
            </div>
            <div class="text-center">{{ $t('actions.text.you can close this page') }}</div>
        </div>
    </div>
</template>

<style scoped>

</style>
