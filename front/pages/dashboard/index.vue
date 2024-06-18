<script setup lang="ts">

const tasks = useTasksStore()
const datasets = useDatasetsStore()
const models = useModelsStore()

onMounted(() => {
    datasets.loadDatasets()
    tasks.load()
    models.load()
})

</script>

<template>
    <div class="flex gap-5">
        <div class="w-full">
            <div class="w-full flex gap-5">
                <div class="w-1/2 2xl:w-1/4 overflow-hidden">
                    <FormsMakePhoto/>
                </div>
                <div class="w-1/2 2xl:w-3/4">
                    <UCard>
                        <div class="flex flex-wrap gap-5">
                            <div class="w-full overflow-hidden flex flex-wrap" v-for="task in tasks.imageTasks">
                                <div class="w-full flex justify-center flex-col">
                                    <div class="flex items-center"
                                         :class="{'text-success-600' : task.status !== 'failed', 'text-error-500' : task.status === 'failed'}">
                                        <UIcon name="loading" class="animate-spin" dynamic
                                               v-if="task.status !== 'completed'"/>
                                        <UIcon name="success" dynamic v-else/>
                                        <div>{{ task.status }}</div>
                                    </div>
                                </div>
                                <div
                                    v-if="task.images && task.images.length"
                                    class="grid grid-cols-2 gap-1 w-full rounded-xl overflow-hidden"
                                >
                                    <div
                                        class="w-full"
                                        :class="{
                        '': task.images.length > 1,
                        'col-span-2': task.images.length === 1,
                    }"
                                        v-for="image in task.images"
                                    >
                                        <ElementsImage :src="image.src" :alt="image.alt"/>
                                    </div>
                                </div>
                                <div v-else class="w-full h-full relative">
                                    <div class="absolute z-10 inset-center">
                                        <UButton>{{ $t('button.text.stop') }}</UButton>
                                    </div>
                                    <USkeleton class="h-52 w-full"/>
                                </div>
                            </div>
                        </div>
                    </UCard>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
