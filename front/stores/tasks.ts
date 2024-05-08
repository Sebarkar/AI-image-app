import {defineStore} from 'pinia'
export const useTasksStore = defineStore('tasks', () => {
    const toast = useToast()
    const {t} = useI18n()

    const tasks = ref([])

    const changeTask = (task) => {
        const index = tasks.value.findIndex(o => o.id === task.id)
        tasks.value[index] = task
        toast.add({color: 'green', title: t('toast.generation finished'), icon: 'i-clarity-success-standard-line'})
    }

    const addTask = (task) => {
        tasks.value.unshift(task)
        toast.add({color: 'green', title: t('toast.generation started'), icon: 'i-clarity-success-standard-line'})
    }

    return {
        tasks,
        changeTask,
        addTask
    };
})
