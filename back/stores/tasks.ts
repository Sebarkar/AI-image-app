import {defineStore} from 'pinia'
export const useTasksStore = defineStore('tasks', () => {
    const snackbar = useSnackbarStore();
    const {t} = useI18n()

    const tasks = ref([])

    const changeTask = (task) => {
        const index = tasks.value.findIndex(o => o.id === task.id)
        tasks.value[index] = task
        snackbar.add({color: 'green', text: t('toast.generation finished')})
    }

    const addTask = (task) => {
        tasks.value.unshift(task)
        snackbar.add({color: 'green', text: t('toast.generation started')})
    }

    return {
        tasks,
        changeTask,
        addTask
    };
})
