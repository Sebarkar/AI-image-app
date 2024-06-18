<script setup lang="ts">
const modals = useModalsStore()

const formContainer = ref(null)
const isOpen = ref(false)
const loading = ref(false)

watch(() => modals.isModalOpened('add-dataset'), (val) => {
    isOpen.value = val
})

function submit() {
    formContainer.value.submit()
}

</script>

<template>
    <ModalsModalContainer
        @close="modals.closeModal('add-dataset')"
        v-model="isOpen"
        classProp="size-full flex flex-center"
        content-class="max-w-3xl w-full bg-white rounded-xl shadow-2xl p-5 overflow-hidden"
    >
        <template #header>
            <h2>{{ $t('forms.text.add new dataset') }}</h2>
        </template>
        <div class="max-h-[800px] w-full">
            <FormsAddNewDataset ref="formContainer" class="w-full" @load="loading = true" @loaded="true"/>
        </div>
        <template #actions>
            <div class="mt-5 grid grid-cols-2 justify-between gap-5 w-full">
                <div class="w-full">
                    <UButton block variant="outline" @click="modals.closeModal('add-dataset')">
                        {{ $t('modal.text.Close') }}
                    </UButton>
                </div>
                <div class="w-full">
                    <UButton block @click="submit()" :loading="loading">
                        {{ $t('forms.create datasets') }}
                    </UButton>
                </div>
            </div>
        </template>
    </ModalsModalContainer>
</template>

<style scoped>

</style>
