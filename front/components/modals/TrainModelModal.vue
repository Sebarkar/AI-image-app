<script setup lang="ts">
const modals = useModalsStore()
const datasets = useDatasetsStore()
const models = useModelsStore()

const formContainer = ref(null)
const isOpen = ref(false)
const form = ref(null)
const loading = ref(false)

watch(() => modals.isModalOpened('train-model'), (val) => {
    isOpen.value = val
})

const images = computed(() => {
    if (datasets.selectedDataset) {
        return datasets.selectedDataset.images
    }
    return [];
})

const trainFields = computed(() => {
    if (models.selectedModel?.train) {
        return models.selectedModel.train
    }
    return []
})

const submit = () => {
    form.value.submit()
}

</script>

<template>
    <ModalsModalContainer
        @close="modals.closeModal('train-model')"
        v-model="isOpen"
        classProp="size-full flex flex-center"
        content-class="max-w-3xl w-full bg-white rounded-xl shadow-2xl p-5 overflow-hidden"
    >
        <template #header>
            <h2>{{ $t('forms.text.Fine-Tune on dataset') }}</h2>
        </template>
        <div class="max-h-[800px] w-full flex flex-wrap items-center justify-between">
            <ElementsTrainingIndicatorBlock
                class="w-full"
                size="3xl"
                :images="images"
                :model="models.selectedModel"
            />
            <FormsModelsModelForm
                ref="form"
                class="pb-10"
                endpoint="train"
                @loading="loading = $event"
                :additional-data="{
                    model_name: models.selectedModel?.name,
                    model_owner: models.selectedModel?.owner,
                }"
                v-if="trainFields"
                :fields="trainFields"
            />
        </div>
        <template #actions>
            <div class="mt-5 grid grid-cols-2 justify-between gap-5 w-full">
                <div class="w-full">
                    <UButton block variant="outline" @click="modals.closeModal('train-model')">
                        {{ $t('modal.text.Close') }}
                    </UButton>
                </div>
                <div class="w-full">
                    <UButton block @click="submit()" :loading="loading">
                        {{ $t('forms.text.Train Model') }}
                    </UButton>
                </div>
            </div>
        </template>
    </ModalsModalContainer>
</template>

<style scoped>

</style>
