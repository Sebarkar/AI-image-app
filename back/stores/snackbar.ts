import {defineStore} from 'pinia'

export const useSnackbarStore = defineStore('snackbar', () => {
    const text = ref('')
    const color = ref('')
    const snackbars = ref([])

    const add = (item: Object) => {
        item.id = snackbars.value.length
        snackbars.value.push(item)
    }

    const remove = (item: { color: string, text: string }) => {
        setTimeout(() => {
            let index = snackbars.value.findIndex(obj => obj.id === item.id)
            snackbars.value.splice(index, 1)
        }, 500)
    }

    return {
        text,
        color,
        add,
        remove,
        snackbars
    };
})