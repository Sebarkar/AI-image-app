import {defineStore} from 'pinia'
export const useModalsStore = defineStore('modals', () => {
    const modals = ref([])

    const isModalOpened = (title: string) => {
        return modals.value.includes(title)
    }

    const openModal = (title: string) => {
        modals.value.push(title)
    }

    const closeModal = (title: string) => {
        let index = modals.value.indexOf(title)
        modals.value.splice(index, 1)
    }

    return {
        isModalOpened,
        closeModal,
        openModal
    };
})
