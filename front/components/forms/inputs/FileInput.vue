<script setup lang="ts">

const input = ref(null)

const emits = defineEmits<{
    (e: 'input'): void
}>()

function getURL(image) {
    if (image) {
        return URL.createObjectURL(image)
    }
    return ''
}

const currentImageUrl = ref('')

function addImage(image) {
    emits('input', image[0])
    currentImageUrl.value = getURL(image[0])
}

function removeFile() {
    emits('input', null)
    currentImageUrl.value = ''
    input.value.value = null
}
</script>

<template>
    <div class="w-full">
        <input
            type="file"
            ref="input"
            @change="addImage($event.target.files)"
            class="relative mb-1 block w-full disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 rounded-md placeholder-gray-400 dark:placeholder-gray-500 file:cursor-pointer file:rounded-l-md file:font-medium file:m-0 file:border-0 file:ring-1 file:py-2.5 file:px-3.5 file:ring-gray-300 dark:file:ring-gray-700 file:text-gray-900 dark:file:text-white file:mr-2 file:bg-gray-50  hover:file:bg-gray-100 dark:file:bg-gray-800 dark:hover:file:bg-gray-700/50 text-base pr-3.5 shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white ring-1 ring-inset file:ring-inset ring-gray-300 dark:ring-gray-700 file:focus:ring-2 focus:ring-2 file:focus:ring-primary-500 focus:ring-primary-500 dark:focus:ring-primary-400"
            name="files"
            id="files"
            accept="image/*"
        >
        <Transition name="fade" tag="div">
            <div class="w-full aspect-square relative" v-if="currentImageUrl">
                <UButton size="xs" color="error" @click="removeFile"
                         class="absolute top-2 right-1 rounded-full z-10" icon="remove"/>
                <div class="w-full h-full rounded-2xl overflow-hidden">
                    <ElementsImage :src="currentImageUrl" class="w-full h-full object-cover"/>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>

</style>
