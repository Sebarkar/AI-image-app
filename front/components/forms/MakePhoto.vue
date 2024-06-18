<script setup lang="ts">
import type {Avatar} from '#ui/types'

const datasets = useDatasetsStore()
const modals = useModalsStore()
const models = useModelsStore()

const validate = (state: any): FormError[] => {
    const errors = []
    if (!state.email) errors.push({path: 'email', message: 'Required'})
    if (!state.password) errors.push({path: 'password', message: 'Required'})
    return errors
}

const onSubmit = () => {
    const formData = new FormData()
    state.images.forEach((image: any, key) => {
        formData.append('file' + key, image)
    })

    state.images = formData;

    useApiFetch('predict', {
        method: 'POST',
        body: state
    })
}

const currentDatasetKey = ref('')

const onError = (error: any) => {
    console.log('error', error)
}

const state = reactive({
    selectedDataset: {},
    images: [],
    prompt: 'Photo of man on boat',
    negative_prompt: '(worst quality, low quality, normal quality, lowres, low details, oversaturated, undersaturated, overexposed, underexposed, grayscale, bw, bad photo, bad photography, bad art:1.4), (watermark, signature, text font, username, error, logo, words, letters, digits, autograph, trademark, name:1.2), (blur, blurry, grainy), morbid, ugly, asymmetrical, mutated malformed, mutilated, poorly lit, bad shadow, draft, cropped, out of frame, cut off, censored, jpeg artifacts, out of focus, glitch, duplicate, (airbrushed, cartoon, anime, semi-realistic, cgi, render, blender, digital art, manga, amateur:1.3), (3D ,3D Game, 3D Game Scene, 3D Character:1.1), (bad hands, bad anatomy, bad body, bad face, bad teeth, bad arms, bad legs, deformities:1.3)',
    width: 1024,
    height: 1024,
    num_inference_steps: 50,
    scheduler: 'DDIM',
    guidance_scale: 7.5,
    mask: '',
    num_outputs: 1,
    lora_scale: 1,
    refine: 'expert_ensemble_refiner',
    refine_steps: 20,
    high_noise_frac: 0.8,
})

const preparedDataSets = computed(() => {
    return datasets.availableDatasets.filter((d) => d.images.length).map((dataset: any) => {
        return {
            id: dataset.id,
            label: dataset.title,
            status: dataset.status,
            token: dataset.token,
            avatar: {
                src: dataset.images[0].src,
            },
            images: dataset.images
        }
    })
})

watch(() => preparedDataSets, (val) => {
    if (!preparedDataSets.value.length) {
        return false;
    }

    datasets.selectDataset(preparedDataSets.value[0])
    if (currentDatasetKey.value) {
        state.prompt = state.prompt.replace(new RegExp(currentDatasetKey.value, 'g'), datasets.selectedDataset.token);
    } else {
        state.prompt = 'A photo of ' + datasets.selectedDataset.token;
    }
    currentDatasetKey.value = datasets.selectedDataset.token
}, {deep: true})

onMounted(() => {
    if (preparedDataSets.value.length) {
        datasets.selectDataset(preparedDataSets.value[0])
        state.prompt = 'A photo of ' + state.selectedDataset.token;
    }
})

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

//DELETE
function getModels() {
    useApiFetch('get-models', {
        method: 'POST',
    })
}

</script>

<template>
    <UForm
        :validate="validate"
        :state="state"
        class="space-y-4 w-full"
        @submit="onSubmit"
        @error="onError"
    >
        <UCard class="w-full" :ui="{
            base: 'overflow-hidden',
            header: {
                base: '',
                background: '',
                padding: 'px-0 py-0 sm:px-0',
            },
        }">
            <template #header>
                <PickersModelPicker class="col-span-5 w-full z-10"/>
            </template>
            <div class="flex flex-col gap-5 w-full">
                <div class="grid items-end gap-5 justify-between w-full grid-cols-5">
                    <div v-if="models.selectedModel && models.selectedModel.user_fine_tune"
                         class="grid items-end gap-5 justify-between w-full grid-cols-5 col-span-5">
                        <UAlert
                            v-if="datasets.selectedDataset && datasets.selectedDataset.status === 'completed'"
                            class="col-span-5"
                            icon="i-heroicons-command-line"
                            color="primary"
                            variant="solid"
                            :title="$t('input.text.Ready')"
                            :description="$t('input.text.Selected Model already Fine-Tuned with this dataset. You can start generate images')"
                        />
                        <ElementsTrainingIndicatorBlock
                            v-else-if="datasets.selectedDataset && datasets.selectedDataset.status === 'running'"
                            class="col-span-5"
                            size="2xl"
                            loading
                            :images="datasets.selectedDataset.images"
                            :model="models.selectedModel"
                        />
                        <UAlert
                            v-else
                            class="col-span-5"
                            icon="i-heroicons-command-line"
                            color="gray"
                            :actions="[{ variant: 'solid', color: 'primary', click: () => modals.openModal('train-model'), label: $t('input.text.Start Fine-Tune Process') }]"
                            variant="solid"
                            :title="$t('input.text.Fine-tune the model')"
                            :description="$t('input.text.This model use parent model data. Fine-Tune model by you own data. It will take 5-30 minutes')"
                        />
                    </div>
                </div>
                <div> <UButton @click="getModels">dsds</UButton></div>
                <FormsModelsModelForm
                    v-if="models.selectedModel && models.selectedModel.predict"
                    :fields="models.selectedModel.predict"
                />
                <div v-else>
                    <USkeleton class="w-full h-20 mb-5" v-for="id in 10"/>
                </div>
            </div>


            <template #footer>
                <UButton @click="onSubmit" class="mt-4" variant="solid" color="primary">Submit</UButton>
            </template>
        </UCard>
    </UForm>
</template>

<style scoped>

</style>
