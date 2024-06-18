<script setup lang="ts">
definePageMeta({
    layout: false,
    middleware: ['sanctum:guest'],
})
</script>

<template>
    <div class="relative h-screen w-full pt-10">
        <div class="flex flex-center flex-col">
            <div class="flex flex-col bg-white md:px-10 px-2 pt-10 rounded-xl mb-5">
                <div class="text-center mb-20">
                    <h1 class="font-bold">{{ $t('auth.text.Sign In With Code') }}</h1>
                </div>
                <FormsAuthOneTimeSignInForm />
            </div>
            <UButton variant="link" block :to="localePath('/auth/sign-in')">
                {{ $t('auth.text.back to sign in') }}
            </UButton>
        </div>
    </div>
</template>

<style scoped>

</style>
