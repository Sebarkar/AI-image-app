<script setup lang="ts">
import {VueFinalModal} from 'vue-final-modal'

const props = defineProps({
    title: {
        type: String,
        default: 'Confirm'
    },
    classProp: {
        type: String,
        default: 'w-full h-full flex justify-end items-center'
    },
    contentClass: {
        type: String,
        default: 'relative bg-white shadow dark:bg-gray-700 max-w-2xl right-0 h-screen'
    },
    fullscreen: {
        type: Boolean,
        default: false
    }
})

function valueChanged(value: boolean) {
    if (!value) {
        emit('close')
    }
}

const emit = defineEmits<{
    (e: 'confirm'): void,
    (e: 'close'): void,
}>()
</script>

<template>
    <VueFinalModal
        :class="classProp"
        :content-class="contentClass"
        click-to-close
        overlay-transition="vfm-fade"
        content-transition="vfm-slide-right"
        @update:modelValue="valueChanged"
    >
        <div class="relative w-full max-h-full">
            <div class="flex flex-col justify-between h-full">
                <div
                    class="flex items-center justify-between p-4 md:p-5"
                >
                    <slot name="header"/>
                    <UButton icon="close" variant="ghost" @click="emit('close')"/>
                </div>
                <div class="py-4 md:py-5 space-y-4 px-4 md:px-5">
                    <div class="max-w-full w-full">
                        <slot/>
                    </div>
                </div>
                <div
                    class="flex items-center p-4 md:p-5" v-if="$slots.actions"
                >
                    <slot name="actions"/>
                </div>
            </div>
        </div>
    </VueFinalModal>
</template>

<style scoped>

</style>
