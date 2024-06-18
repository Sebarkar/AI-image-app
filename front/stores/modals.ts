import {defineStore} from 'pinia'

export const useModalsStore = defineStore('modals', () => {
    const modals = ref([])

    const isModalOpened = (title: string) => {
        return modals.value.includes(title)
    }

    const openModal = (title: string) => {
        console.log(title)
        return modals.value.includes(title) ? '' : modals.value.push(title);
    }

    const closeModal = (title: string) => {
        let index = modals.value.indexOf(title)
        if (index > -1)
            modals.value.splice(index, 1)
    }

    return {
        isModalOpened,
        closeModal,
        openModal
    };
})
