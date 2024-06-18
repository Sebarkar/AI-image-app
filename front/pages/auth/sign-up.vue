<script setup lang="ts">
definePageMeta({
    layout: 'default',
    middleware: ['sanctum:guest'],
    key: 'auth-sign-up',
})
</script>

<template>
    <div class="relative h-screen w-full pt-10">
        <FormsAuthSignUpForm/>
    </div>
</template>

<style scoped>

</style>
