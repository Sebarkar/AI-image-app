<script setup lang="ts">
const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        default: 'image',
    },
})
</script>

<template>
    <NuxtImg :src="src" :alt="alt" loading="lazy" placeholder/>
</template>

<style scoped>

</style>
