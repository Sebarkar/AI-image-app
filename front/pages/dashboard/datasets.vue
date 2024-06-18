<script setup lang="ts">

const datasets = useDatasetsStore()
const columns = [
    {
        key: 'id',
        label: 'ID'
    },
    {
        key: 'title',
        label: 'Title',
        sortable: true,
    }
]

const selected = ref([])

function removeDataset(ids) {
    if (Array.isArray(ids)) {
        datasets.removeDatasets(ids)
    } else {
        datasets.removeDatasets([ids])
    }
}

</script>

<template>
    <div class="flex gap-5">
        <UTable
            :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
            :columns="columns"
            :rows="datasets.datasets"
            v-model="selected"
        >
            <template #id-data="{ row }">
                <UButton size="xs" icon="remove" color="error" @click="removeDataset(row.id)"/>
            </template>
            <template #empty-state>
                <div class="flex flex-col items-center justify-center py-6 gap-3">
                    <span class="italic text-sm">{{ $t('datasets.text.no datasets') }}</span>
                    <UButton label="Add dataset" />
                </div>
            </template>
        </UTable>
    </div>
</template>

<style scoped>

</style>
