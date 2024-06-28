<script setup lang="ts">
const listener = useListenerStore()

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        default: 'image',
    },
    draggable: Boolean,
    dragTarget: String,
})

function onDragStart(event) {
    if (!props.draggable) {
        return
    }
    listener.setDragTarget(props.dragTarget)
    event.dataTransfer.setData('text/plain', props.src);
}

function onDragEnd() {
    listener.stopDragging(props.dragTarget)
}

const imageLoaded = ref(false)

const handleImageLoaded = () => {
    imageLoaded.value = true
}
</script>

<template>
    <div class=" w-full h-full"
    >
        <USkeleton class="h-full w-full absolute left-0 top-0 z-10" v-if="!imageLoaded"/>
        <NuxtImg
            @load="handleImageLoaded"
            :src="src"
            :alt="alt"
            loading="lazy"
            class="object-cover w-full h-full"
            draggable="true"
            @dragstart="onDragStart"
            @dragend="onDragEnd"
        />
    </div>
</template>

<style scoped>

</style>
