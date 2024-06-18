<script setup lang="ts">

const isOpen = ref(false)
const modals = useModalsStore()

watch(() => isOpen.value, (val) => {
    if (val) {
        modals.openModal('create-model')
    } else {
        modals.closeModal('create-model')
    }
})

const fields = ref([])
const form = ref(null)
const loading = ref(false)

const initFields = async () => {
    fields.value = await useApiFetch('get-creating-model-form', {
        method: 'POST'
    })
}
onMounted(() => {
    initFields()
})

watch(() => modals.isModalOpened('create-model'), (val) => {
    isOpen.value = val
})

const submit = () => {
    form.value.submit()
}
</script>

<template>
    <ModalsModalContainer
        @close="modals.closeModal('create-model')"
        v-model="isOpen"
        classProp="size-full flex flex-center "
        content-class="max-w-3xl w-full  bg-white rounded-xl shadow-2xl p-5 "
    >
        <template #header>
            <h2>{{ $t('forms.text.Create new model') }}</h2>
        </template>
        <div class="w-full max-h-[800px]">
            <FormsModelsModelForm ref="form" :fields="fields" endpoint="create-model" @loading="loading = $event"/>
        </div>
        <template #actions>
            <div class="mt-5 grid grid-cols-2 justify-between gap-5 w-full">
                <div class="w-full">
                    <UButton block variant="outline" @click="modals.closeModal('create-model')">
                        {{ $t('modal.text.Close') }}
                    </UButton>
                </div>
                <div class="w-full">
                    <UButton block @click="submit()" :loading="loading">
                        {{ $t('forms.text.Create Model') }}
                    </UButton>
                </div>
            </div>
        </template>
    </ModalsModalContainer>
</template>

<style scoped>

</style>
