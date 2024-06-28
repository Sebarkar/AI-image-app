<script setup lang="ts">
const noImagesError = ref(false)
const loading = ref(false)
const imageUploader = ref(null)

const state = reactive({
    images: [],
    imagesDataset: new FormData(),
})

const props = defineProps<{
    errors?: [undefined, Object]
}>()

const errorsState = ref(props.errors)

watch(() => props.errors, () => {
    prepareErrors()
})

function prepareErrors() {
    if (props.errors) {
        let errorsObject = {};
        //Add names to errors
        state.images.map((file: any) => file.name).forEach((name: any, index) => {
            let i = props.errors.findIndex((error: any) => +error.path === index)
            if (i  > -1) {
                errorsObject[name] = props.errors[i]['message']
            }
        })
        errorsState.value = errorsObject;
    }
}

function dropErrorName(name: string) {
    errorsState[name] = null
}

function resetImageUploader(index: any) {
    imageUploader.value.value = ''
    noImagesError.value = false;
    errorsState.value = {};
}

function addImages(inputFiles: any) {
    const files = Array.from(inputFiles)
    const names = state.images.map((file: any) => file.name)
    if (files.length) {
        files.forEach((image: any) => {
            if (!names.includes(image.name)) {
                state.images.push(image)
            }
        })
    }
    changeFilesInput();
}

const images = computed(() => {
    if (state.images && state.images.length) {
        return state.images.map((image: any) => {
            return {
                src: URL.createObjectURL(image),
                key: image.name,
            }
        })
    }
    return []
})

const emits = defineEmits(['input', 'files-dropped'])

watch(() => images.value, (val) => {
    emits('input', state.images)
})

function checkImages() {
    if (state.images.length === 0) {
        noImagesError.value = true;
        return false
    }
    return true
}

function removeImage(index: any, name: string) {
    state.images.splice(index, 1)
    dropErrorName(name)
    changeFilesInput();
}

function changeFilesInput() {
    let dt = new DataTransfer()
    let files = Array.from(imageUploader.value.files)
    files.forEach((file: any) => {
        if (state.images.map(image => image.name).includes(file.name)) {
            dt.items.add(file)
        }
    })

    imageUploader.value.files = dt.files;
}

// Create `active` state and manage it with functions
let active = ref(false)
let inActiveTimeout = null // add a variable to hold the timeout key

function setActive() {
    active.value = true
    clearTimeout(inActiveTimeout) // clear the timeout
}

function setInactive() {
    inActiveTimeout = setTimeout(() => {
        active.value = false
    }, 50)
}

function onDrop(e) {
    addImages(e.dataTransfer.files)
    imageUploader.value.files = e.dataTransfer.files;
    setInactive() // add this line too
}

const validate = (state: any) => {
    return checkImages();
}
</script>

<template>
    <TransitionGroup class="flex gap-2 flex-wrap relative mb-5 py-2" tag="div" name="list"
                     :data-active="active"
                     @dragenter.prevent="setActive"
                     @dragover.prevent="setActive"
                     @dragleave.prevent="setInactive"
                     @drop.prevent="onDrop"
    >
        <label

            for="files"
            :key="'sdsdda'"
            class="h-32 rounded-2xl bg-white flex flex-center flex-col  border text-center mb-5 duration-200 cursor-pointer"
            :class="{
                    'w-full bg-stripes': active,
                    'w-32': !active,
                    'border-error-500 text-error-500': noImagesError,
                    'border-v1primary-300': !noImagesError
                }"
        >
            <span class="text-xl font-bold block uppercase">{{ $t('forms.text.+add photos') }}</span>
            <UIcon name="upload" class="w-10 h-10"></UIcon>
            <input
                :disabled="loading"
                @click="resetImageUploader"
                ref="imageUploader"
                type="file"
                required
                multiple
                class="w-0 h-0"
                name="files"
                id="files"
                accept="image/*"
                @change="addImages($event.target.files)"
            >
        </label>
        <div
            v-for="(image, index) in images"
            :key="image.key"
            class="w-32 h-32 rounded-xl relative"
        >
            <UButton size="xs" color="error" @click="removeImage(index, image.key)" :disabled="loading"
                     class="absolute -top-1 -right-1 rounded-full z-10" icon="remove"/>
            <div class="w-full h-full relative z-0">
                <img :src="image.src" alt="image" class="w-full h-full block object-cover rounded-xl">
                <Transition name="fade" class="absolute inset-center" tag="div">
                    <div v-if="errorsState && errorsState[image.key]" class=" rounded-xl text-2xs p-2 bg-error-300 text-center">
                        {{ errorsState[image.key] }}
                    </div>
                </Transition>
            </div>
        </div>
    </TransitionGroup>
</template>

<style scoped>

</style>
