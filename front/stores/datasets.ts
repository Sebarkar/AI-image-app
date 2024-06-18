import {defineStore} from 'pinia'
import {array} from "yup";
export const useDatasetsStore = defineStore('datasets', () => {
    const toast = useToast()
    const models = useModelsStore()
    const {t} = useI18n()

    const datasets = ref([])

    const addDataset = (dataset) => {
        datasets.value.unshift(dataset)
        toast.add({color: 'green', title: t('toast.dataset saved successfully'), icon: 'success'})
    }

    const imageTasks = computed(() => datasets.value);
    const selectedDataset = ref({});

    async function loadDatasets() {
        const data = await useApiFetch('datasets', {
            method: 'POST',
        })
        data.forEach((dataset) => {
            datasets.value.push(dataset)
        })
    }

    function changeDataset(dataset) {
        const index = datasets.value.findIndex((d) => d.id === dataset.id)
        toast.add({color: 'green', title: dataset.title + ': ' + t('toast.dataset trained successfully'), icon: 'success'})
        if (index !== -1) {
            datasets.value.splice(index, 1, dataset)
        }
    }

    function selectDataset(dataset) {
        selectedDataset.value = dataset
    }

    const availableDatasets = computed(() => {
        if (models.selectedModel) {
            return datasets.value.filter((dataset) => dataset.model_owner === models.selectedModel.name)
        }
        return [];
    });

    function removeDatasets(sets: array) {
        sets.forEach((id) => {
            const index = datasets.value.findIndex(d => d.id === id)
            console.log(index)
            if (index !== -1) {
                datasets.value.splice(index, 1)
            }

        })

        useApiFetch('remove-datasets', {
            method: 'POST',
            body: {
                ids: sets
            }
        })
    }

    return {
        datasets,
        addDataset,
        removeDatasets,
        selectedDataset,
        availableDatasets,
        selectDataset,
        imageTasks,
        loadDatasets,
    };
})
