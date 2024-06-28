import {defineStore} from 'pinia'
export const useTasksStore = defineStore('tasks', () => {
    const toast = useToast()
    const {t} = useI18n()

    const tasks = ref([])
    const trainings = ref([])
    const predicts = ref([])

    const changeTask = (task, message) => {
        const taskList = getTaskList(task.type)
        const index = taskList.findIndex(o => o.id === task.id)
        if (index !== -1) {
            taskList[index] = task
        }
        toast.add({color: 'green', title: message, icon: 'i-clarity-success-standard-line'})
    }

    function getTaskList(taskType :string) {
        switch (taskType) {
            case 'training':
                return trainings.value
            case 'predict':
                return predicts.value
            default:
                return tasks.value
        }

    }

    async function load(taskType :string) {
        const data = await useApiFetch('tasks-' + taskType, {
            method: 'POST',
        })
        const taskList = getTaskList(taskType)
        data.forEach((task) => {
            taskList.push(task)
        })
    }

    const addTask = (task, message) => {
        const taskList = getTaskList(task.type)
        taskList.unshift(task)
        toast.add({color: 'green', title: message, icon: 'i-clarity-success-standard-line'})
    }

    const imagePredicts = computed(() => predicts.value);

    return {
        tasks,
        trainings,
        predicts,
        changeTask,
        addTask,
        load,
        imagePredicts
    };
})
