import {defineStore} from 'pinia'

export const useListenerStore = defineStore('listener', () => {
    const auth = useAuthStore();
    const tasks = useTasksStore();
    const models = useModelsStore();

    const dragListener = ref([]);

    function setDragTarget(target) {
        dragListener.value.push(target);
    }

    function stopDragging(target) {
        dragListener.value.splice(dragListener.value.indexOf(target), 1);
    }

    function isDragging(target) {
        return dragListener.value.includes(target);
    }

    const register = async (user) => {
        Echo.private(`task.${user.id}`)
            //PREDICT
            .listen(`.predict.finished`, (e) => {
                console.log(e, 'ds');
                tasks.changeTask(e.task, e.message);
            })
            .listen(`.predict.changed`, (e) => {
                console.log(e, 'ds');
                tasks.changeTask(e.task, e.message);
            })
            .listen(`.predict.started`, (e) => {
                console.log(e, 'ds');
                tasks.addTask(e.task, e.message);
            })
            //TRAIN
            .listen(`.train.started`, (e) => {
                models.changeUserModel(e.task.model_id, e.message);
            })
            .listen(`.train.updated`, (e) => {
                models.changeUserModel(e.task.model_id, e.message);
            })
            .listen(`.train.finished`, (e) => {
                models.changeUserModel(e.task.model_id, e.message);
            });
        Echo.private(`user.${user.id}`)
            .listen(`.auth.verified`, () => {
                if (auth.user) {
                    auth.user.email_verified_at = new Date();
                }
            });
        Echo.private(`model.${user.id}`)
            .listen(`.model.created`, (e) => {
                models.addUserModel(e.model_id, e.message);
            })
    }

    return {
        register,
        isDragging,
        stopDragging,
        setDragTarget,
        dragListener
    };
})
