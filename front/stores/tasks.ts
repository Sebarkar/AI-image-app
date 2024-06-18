import {defineStore} from 'pinia'
export const useTasksStore = defineStore('tasks', () => {
    const toast = useToast()
    const {t} = useI18n()

    const tasks = ref([])

    const changeTask = (task) => {
        const index = tasks.value.findIndex(o => o.id === task.id)
        tasks.value[index] = task
        toast.add({color: 'green', title: t('toast.status updated'), icon: 'i-clarity-success-standard-line'})
    }

    async function load() {
        const data = await useApiFetch('tasks', {
            method: 'POST',
        })
        data.forEach((task) => {
            tasks.value.push(task)
        })
    }

    const addTask = (task) => {
        tasks.value.unshift(task)
        toast.add({color: 'green', title: t('toast.generation started'), icon: 'i-clarity-success-standard-line'})
    }

    const imageTasks = computed(() => tasks.value);

    return {
        tasks,
        changeTask,
        addTask,
        load,
        imageTasks
    };
})
