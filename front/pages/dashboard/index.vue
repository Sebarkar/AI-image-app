<script setup lang="ts">

const tasks = useTasksStore()
const datasets = useDatasetsStore()
const models = useModelsStore()

onMounted(() => {
    datasets.loadDatasets()
    tasks.load('predict')
    models.load()
})

</script>

<template>
    <div class="flex gap-5 h-full">
        <div class="w-full h-full">
            <div class="w-full flex gap-5 h-full">
                <div class="w-1/2 2xl:w-1/4 h-full">
                    <FormsMakePhoto class="h-full"/>
                </div>
                <div class="w-1/2 2xl:w-3/4 sticky top-0 h-full">
                    <BlocksTaskFullBlock :tasks="tasks.imagePredicts"/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
