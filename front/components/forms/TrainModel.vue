<script setup lang="ts">

const {t} = useI18n();

const datasets = useDatasetsStore()
const models = useModelsStore()

const onSubmit = () => {
    models.train(datasets.selectedDataset, state)
}


const loading = ref(false)

</script>

<template>
    <UForm
        ref="form"
        :state="state"
        :schema="schema"
        class="space-y-4 max-w-full"
        @submit="onSubmit"
        :validateOn="['input', 'submit']"
        @error="onError"
    >
        <div class="grid grid-cols-12 gap-5" >

        </div>
    </UForm>
</template>

<style scoped>

</style>
