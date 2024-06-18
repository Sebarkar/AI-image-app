<script setup lang="ts">
import { setGallery, Gallery } from 'vue-preview-imgs';

const images = useImagesStore()
</script>

<template>
    <Gallery :list="images.preparedImages" :config="{ immediate: false }"/>
</template>

<style scoped>

</style>
