<script setup lang="ts">
definePageMeta({
    layout: 'default',
    middleware: ['sanctum:guest'],
    key: 'auth-sign-in',
})

</script>

<template>
    <div class="relative h-screen w-full pt-10">
        <FormsAuthSignInForm/>
    </div>
</template>

<style scoped>

</style>
