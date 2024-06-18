import {defineStore} from 'pinia'
import {array} from "yup";
export const useImagesStore = defineStore('images', () => {

    const tasks = useTasksStore();

    const images = computed(() => {
        let array = [];
        tasks.tasks.forEach((task) => {
            task.images.forEach((img) => {
                array.push(img)
            });
        })
    })

    const preparedImages = computed(() => {
        return images.value.map((i) => {
            return {
                href: i.src,
                thumbnail: i.src,
                width: 900,
                height: 900,
            }
        })
    })

    return {
        preparedImages
    };
})
