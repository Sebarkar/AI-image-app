<script setup lang="ts">
defineProps({
    tasks: Array,
})
const images = useImagesStore()
const modals = useModalsStore()
</script>

<template>
    <UCard
        class="sticky top-0"
        :ui="{
                            base: 'overflow-hidden h-full py-5',
                            divide: '',
                            body: {
                                base: 'overflow-y-scroll h-full h-full',
                            },
                        }"
    >
        <TransitionGroup name="list-left" class="flex flex-wrap gap-5 columns-3 h-full " tag="div">
            <div class="w-full xl:w-1/4 overflow-hidden rounded-xl flex flex-wrap" v-for="task in tasks" :key="task.id">
                <div class="w-full flex justify-center flex-col">
                    <div
                        class="flex items-center"
                        :class="{
                                            'text-yellow-600': task.status === 'awaiting',
                                            'text-success-600': task.status === 'completed',
                                            'text-error-600': task.status === 'error',
                                            'text-yellow-800': task.status === 'running',
                                        }"
                    >
                        <UIcon name="await" dynamic
                               v-if="task.status === 'awaiting'"/>
                        <UIcon name="loading" class="animate-spin" dynamic
                               v-else-if="task.status === 'running'"/>
                        <UIcon name="loading" dynamic
                               v-else-if="task.status === 'error'"/>
                        <UIcon v-else-if="task.status === 'completed'"
                               name="success" dynamic/>
                        <div class="pr-1">{{ task.status }}</div>
                    </div>
                </div>
                <div
                    v-if="task.images && task.images.length"
                    class="grid grid-cols-2 gap-1 w-full rounded-xl overflow-hidden"
                >
                    <div
                        class="w-full relative cursor-pointer"
                        :class="{
                        '': task.images.length === 2,
                        'col-span-2': task.images.length === 1 || task.images.length === 3 && id === 2,
                    }"
                        v-for="(image, id) in task.images"
                        @click="images.selectedImage = image; modals.openModal('image-viewer')"
                    >
                        <ElementsImage
                            :src="image.src"
                            :alt="image.alt"
                            :draggable="true"
                            drag-target="input_image"
                            class="w-full h-full"
                        />
                    </div>
                </div>
                <div v-else class="w-full h-full relative">
                    <div class="absolute z-10 inset-center">
                        <UButton>{{ $t('button.text.stop') }}</UButton>
                    </div>
                    <USkeleton class="h-52 w-full"/>
                </div>
            </div>
        </TransitionGroup>
    </UCard>
</template>

<style scoped>

</style>
