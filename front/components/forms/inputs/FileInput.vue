<script setup lang="ts">

const listener = useListenerStore()
const input = ref(null)

const emits = defineEmits<{
    (e: 'input', v: File | string | null): void
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

function setFile(file) {
    currentImageUrl.value = getURL(file)
}

function setUrl(url) {
    currentImageUrl.value = url
}

function removeFile() {
    emits('input', null)
    fileUrl.value = ''
    type.value = 'file'
    currentImageUrl.value = ''
    input.value.value = null
}

// Create `active` state and manage it with functions
let active = ref(false)
let inActiveTimeout = null // add a variable to hold the timeout key

function setActive() {
    clearTimeout(inActiveTimeout) // clear the timeout
}

function setInactive() {
    inActiveTimeout = setTimeout(() => {
        active.value = false
    }, 50)
}

const type = ref('file')
const fileUrl = ref('')


function onDrop(e) {
    if (e.dataTransfer.getData('text/plain')) {
        setUrl(e.dataTransfer.getData('text/plain'))
        fileUrl.value = e.dataTransfer.getData('text/plain')
        type.value = 'text'
        emits('input', fileUrl.value)
    } else {
        addImage(e.dataTransfer.files)
    }
    setInactive() // add this line too
}

watch(() => listener.dragListener.length, () => active.value = listener.isDragging('input_image'))

defineExpose({
    setFile,
    removeFile
})
</script>

<template>
    <div
        class="flex gap-2 flex-wrap relative mb-5 py-2 w-full h-full"
        :data-active="active"
        @dragenter.prevent="setActive"
        @dragover.prevent="setActive"
        @drop.prevent="onDrop"
    >
        <input
            :type="type"
            :value="fileUrl"
            ref="input"
            @change="addImage($event.target.files)"
            :class="{
                'file:w-full file:bg-stripes input_file file:text-transparent': active,
                'pr-3.5': !active,
                'input_file': type === 'file',
                'input_string': type === 'text'
            }"
            name="files"
            id="files"
            accept="image/*"
        >
        <div class="absolute inset-center flex flex-center text-md w-full text-v1secondary font-bold" v-show="active">
            <UIcon name="upload" class="w-5 h-5 mr-2"/>
            {{ $t('input.text.drag file available') }}
        </div>
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
