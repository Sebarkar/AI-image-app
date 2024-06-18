<script setup lang="ts">
defineProps({
    images: Array,
    model: Object,
    loading: Boolean,
    size: {
        type: String,
        default: '3xl'
    },
})
</script>

<template>
    <div class="w-full grid grid-cols-[1fr_1fr_1fr] bg-gray-950 text-white p-5 rounded-2xl mb-10">
        <div class="flex aspect-square">
            <div class="flex flex-center w-full">
                <UAvatarGroup :size="size" :max="3">
                    <UAvatar
                        :ui="{
                                size: {
                                   '3xl': 'object-cover h-20 w-20 text-3xl',
                                   '2xl': 'object-cover h-16 w-16 text-2xl',
                                }
                                }"
                        v-for="image in images"
                        :src="image.src"
                        :alt="image.alt"
                    />
                </UAvatarGroup>
            </div>
        </div>
        <div
            class="grow flex flex-center aspect-square"
        >
            <div class="py-2 relative grow w-2/3">
                <div class="absolute inset-x-center z-10 flex flex-center p-1">
                    <UIcon name="loading" class="w-8 h-8 animate-spin" v-if="loading"></UIcon>
                    <UIcon name="upload" v-else class="w-6 h-6"></UIcon>
                </div>
                <svg width="100%" height="100%" viewBox="0 0 300 200" class="rotate-0">
                    <line x1="40" x2="260" y1="100" y2="100" stroke="white" stroke-width="4" stroke-linecap="round"
                          stroke-dasharray="1, 7">
                        <animate
                            v-if="loading"
                            attributeName="stroke-dashoffset"
                            values="100;0"
                            dur="3s"
                            calcMode="linear"
                            repeatCount="indefinite"/>
                    </line>
                </svg>
            </div>
        </div>
        <ElementsModelItem
            :model="model"
            variant="square"
        />

    </div>
</template>

<style scoped>

</style>
