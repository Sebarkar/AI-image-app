<script setup lang="ts">
const modals = useModalsStore()
const models = useModelsStore()

const isOpen = ref(false)

watch(() => modals.isModalOpened('select-model'), (val) => {
    console.log(val)
    isOpen.value = val
})

watch(() => isOpen.value, (val) => {
    if (val) {
        modals.openModal('select-model')
    } else {
        modals.closeModal('select-model')
    }
})

const loaded = computed(() => {
    return models.selectedModel
})

</script>

<template>
    <div>
        <div class="w-full px-2 py-2 bg-v1primary-950" >
            <USkeleton v-if="!loaded" class="w-full h-14 z-10"/>
            <ElementsModelItem
                class="h-14 z-10"
                @click="modals.openModal('select-model')"
                :model="models.selectedModel"
                v-else
                variant="rectangle"
            />
        </div>
        <ModalsModalContainer
            v-model="isOpen"
            fullscreen
            content-class="w-screen h-screen bg-v1primary-950"
            class-prop="w-screen h-screen"
            @close="modals.closeModal('select-model')"
        >
            <template #header>
                <h2 class="text-white tracking-widest col-span-10">Select Model</h2>
            </template>
            <div class="w-full bg-v1primary-950 rounded-none h-screen">
                <div class="p-4 max-h-full grid grid-cols-10 select-none gap-10">
                    <div v-for="(type, index) in models.preparedModels" class="col-span-10 grid grid-cols-10 gap-5 pb-20 ">
                        <TransitionGroup name="fade" tag="div" class="col-span-10 grid grid-cols-10 gap-5">
                        <div v-if="index === 'users'" class="col-span-4" key="fdsf">
                            <div><UButton @click="modals.openModal('create-model')">{{ $t('btn.text.create model') }}</UButton></div>
                        </div>
                        <h3 class="col-span-10 tracking-widest text-white" v-if="type.length" key="12s">{{index}}</h3>
                        <ElementsModelItem
                            class="md:col-span-2 2xl:col-span-1"
                            v-for="model in type"
                            :key="model.title"
                            :model="model"
                            :selected="models.isModelSelected(model)"
                            @select="models.selectModel"
                        />
                        </TransitionGroup>
                    </div>
                </div>
            </div>
        </ModalsModalContainer>
    </div>
</template>

<style scoped>

</style>
