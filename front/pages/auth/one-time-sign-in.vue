<script setup lang="ts">
definePageMeta({
    layout: false,
    middleware: ['sanctum:guest'],
})
</script>

<template>
    <div class="relative h-screen w-full">
        <div class="absolute inset-center">
            <div class="text-center mb-20">
                <h1 class="font-bold">{{ $t('auth.text.Sign In With Code') }}</h1>
            </div>
            <div class="flex flex-center">
                <FormsAuthOneTimeSignInForm />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
