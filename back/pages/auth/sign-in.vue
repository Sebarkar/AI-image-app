<script setup lang="ts">
definePageMeta({
    layout: false,
    middleware: ['sanctum:guest'],
})

</script>

<template>
    <div class="h-screen w-100 d-flex justify-center align-center">
        <TheLogin />
    </div>
</template>

<style scoped>

</style>
