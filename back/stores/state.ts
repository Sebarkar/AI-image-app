import {defineStore} from 'pinia'

export const useStateStore = defineStore('state', () => {
    const drawer = ref(false)

    const changeDrawer = (val = false) => {
        drawer.value = val !== val ? val : !drawer.value;
    }

    return {
        drawer,
        changeDrawer
    };
})
