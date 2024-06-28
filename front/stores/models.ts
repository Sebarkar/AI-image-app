import {defineStore} from 'pinia'
import {a} from "@shikijs/core/chunk-tokens";

export const useModelsStore = defineStore('models', () => {
    const datasets = useDatasetsStore()
    const models = ref([])
    const toast = useToast()
    const {t} = useI18n()

    function load(search = '') {
        useApiFetch('models', {
            method: 'POST',
            body: {
                search: search
            }
        }).then((data) => {
            if (search) {
                models.value = data
            } else {
                data.forEach((model) => {
                    models.value.push(model)
                })
                if (models.value.length > 0) {
                    return selectModel(models.value[0])
                }
            }
        })
    }

    const search = ref('')

    watch(() => search.value, (value) => {
        setTimeout(() => {
            if (value !== search.value) return
            load(value)
        }, 1000);
    })

    const preparedModels = computed(() => {
        let array = {
            'users': [],
            'regular': [],
        };

        models.value.forEach((model) => {
            if (model.user_id) {
                array['users'].push(model)
            } else {
                array['regular'].push(model)
            }
        })
        return array;
    })

    async function train(dataset: any = null, settings: any = null) {
        if (dataset === null) {
            dataset = datasets.selectedDataset
        }
        return await useApiFetch('train', {
            method: 'POST',
            body: {
                'dataset_id': dataset.id,
                'settings': settings,
            }
        })
    }

    const selectedModel = ref(null)

    async function addUserModel(id: number, message : string) {
        return await loadUserModel(id).then((data) => {
            models.value.push(data)
            toast.add({color: 'green', title: message, icon: 'i-clarity-success-standard-line'})
        })
    }

    async function loadUserModel(id: number) {
        return await useApiFetch('get-user-model', {
            method: 'POST',
            body: {
                'model_id': id,
            }
        })
    }

    async function changeUserModel(id: number, message : string) {
        return await loadUserModel(id).then((data) => {
            let index = models.value.findIndex(model => model.id === id)
            if (index !== -1) {
                models.value.splice(index, 1, data)
            }
            if (isModelSelected(data)) {
                selectedModel.value = data
            }
            toast.add({color: 'green', title: message, icon: 'i-clarity-success-standard-line'})
        })
    }

    function isModelSelected(model: any) {
        if (selectedModel.value === null) {
            return false;
        }
        return selectedModel.value.name === model.name && selectedModel.value.owner === model.owner;
    }

    async function selectModel(model: any) {
        if (isModelSelected(model)) {
            return
        }
        selectedModel.value = model
        return useApiFetch('get-model', {
            method: 'POST',
            body: {
                'name': model.name,
                'owner': model.owner,
            }
        }).then((data) => {
            selectedModel.value = data
        }).catch((data) => {
            if (data.status === 451) {
                toast.add({
                    color: 'red',
                    title: t('models.text.Wait Fine-Tune'),
                    description: t('models.text.Model is not ready yet. Please start fine-tune or wait for the fine-tuning to finish.'),
                    icon: 'i-clarity-error-standard-line'
                })
            }
        })
    }

    return {
        models,
        selectedModel,
        preparedModels,
        addUserModel,
        changeUserModel,
        search,
        isModelSelected,
        selectModel,
        train,
        load
    };
})
