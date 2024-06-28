<script setup lang="ts">
const modals = useModalsStore()
const images = useImagesStore()

const formContainer = ref(null)
const isOpen = ref(false)

watch(() => modals.isModalOpened('image-viewer'), (val) => {
    isOpen.value = val
})

function submit() {
    formContainer.value.submit()
}

</script>

<template>
    <ModalsModalContainer
        @close="modals.closeModal('image-viewer')"
        v-model="isOpen"
        classProp="size-full flex flex-center"
        content-class="max-w-3xl w-full bg-white rounded-xl shadow-2xl p-5 overflow-hidden"
    >
        <template #header>
            <h2>{{ $t('forms.text.add new dataset') }}</h2>
        </template>
        <div class="max-h-[800px] w-full">
            <ElementsImage :src="images.selectedImage.src" alt="werwer"></ElementsImage>
        </div>
        <template #actions>
            <div class="mt-5 justify-between w-full">
                <div class="w-full">
                    <UButton block variant="outline" @click="modals.closeModal('image-viewer')">
                        {{ $t('modal.text.Close') }}
                    </UButton>
                </div>
            </div>
        </template>
    </ModalsModalContainer>
</template>

<style scoped>

</style>
