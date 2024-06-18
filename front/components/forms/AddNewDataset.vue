<script setup lang="ts">

import {object, string, number, type InferType} from "yup";

const {t} = useI18n();

const datasets = useDatasetsStore()
const modals = useModalsStore()

const emits = defineEmits({
    saved: () => true,
    load: () => true,
    loaded: () => true,
})

const onSubmit = () => {
    const formData = new FormData()
    loading.value = true
    emits('load')
    state.images.forEach((image: any, key) => {
        formData.append('file' + key, image)
    })

    formData.append('title', state.title)

    useApiFetch('create-dataset', {
        method: 'POST',
        body: formData
    }).then((res) => {
        datasets.addDataset(res)
        modals.closeModal('add-dataset')
        loading.value = false
        emits('loaded')
    })
        .catch((e) => {
            emits('loaded')
            if (e.data.errors) {
                form.value.setErrors(Object.keys(e.data.errors).map((name) => ({
                    message: e.data.errors[name][0],
                    path: name,
                })))
            }
            loading.value = false
        })
}



const schema = object({
    title: string().required(),
})

type Schema = InferType<typeof schema>

const loading = ref(false)

const onError = (error: any) => {
    console.log('error', error)
}

const state = reactive({
    images: [],
    imagesDataset: new FormData(),
    title: undefined,
    type: '',
})

onMounted(() => {
    state.type = 'face'
})

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

const imageUploader = ref(null)

function addImages(e: any) {
    const files = Array.from(e.target.files)
    const names = state.images.map((file: any) => file.name)
    if (files.length) {
        files.forEach((image: any) => {
            if (!names.includes(image.name)) {
                state.images.push(image)
            }
        })
    }
}

const noImagesError = ref(false)

function checkImages() {
    if (state.images.length === 0) {
        noImagesError.value = true;
        return false
    }
    return true
}
const submit = (state: any) => {
    if (checkImages()) {
        form.value.validate()
            .then(() => {
                onSubmit()
            }).catch(() => {})
    }
}




function removeImage(index: any) {
    state.images.splice(index, 1)
}

function resetImageUploader(index: any) {
    imageUploader.value.value = ''
    noImagesError.value = false;
}

const form = ref()
const datasetsPrompts = reactive(
    {
        face: {},
        body: {},
        style: {},
        design: {},
        thing: {},
    }
)

defineExpose({
    submit
})
</script>

<template>
    <UForm
        ref="form"
        :state="state"
        :schema="schema"
        class="space-y-4 max-w-full mb-5"
        @submit="onSubmit"
        :validateOn="['input', 'submit']"
        @error="onError"
    >
        <TransitionGroup class="flex gap-2 flex-wrap relative mb-5 py-2" tag="div" name="list">
            <label
                for="files"
                :key="'sdsdda'"
                class="w-32 h-32 rounded-2xl bg-white flex flex-center flex-col  border text-center mb-5"
                :class="{
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
                    @change="addImages"
                >
            </label>
            <div
                v-for="(image, index) in images"
                :key="image.key"
                class="w-32 h-32 rounded-xl relative"
            >
                <UButton size="xs" color="error" @click="removeImage(index)" :disabled="loading"
                         class="absolute -top-1 -right-1 rounded-full" icon="remove"/>
                <img :src="image.src" alt="image" class="w-full h-full block object-cover rounded-xl">
            </div>
        </TransitionGroup>
        <UFormGroup :label="$t('input.text.Title')" name="title">
            <UInput v-model="state.title"/>
        </UFormGroup>
    </UForm>
</template>

<style scoped>

</style>
