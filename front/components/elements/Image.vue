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

const imageLoaded = ref(false)

const handleImageLoaded = () => {
    imageLoaded.value = true
}
</script>

<template>
    <div class=" w-full h-full">
        <USkeleton class="h-full w-full absolute left-0 top-0 z-10" v-if="!imageLoaded"/>
        <NuxtImg @load="handleImageLoaded" :src="src" :alt="alt" loading="lazy" class="object-cover w-full h-full" />
    </div>
</template>

<style scoped>

</style>
