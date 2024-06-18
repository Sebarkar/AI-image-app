<script setup lang="ts">
import {VueFinalModal} from 'vue-final-modal'

const props = defineProps({
    title: {
        type: String,
        default: 'Confirm'
    },
    classProp: {
        type: String,
        default: 'w-full h-full'
    },
    contentClass: {
        type: String,
        default: 'relative bg-white rounded-lg shadow dark:bg-gray-700'
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
        :click-to-close="false"
        overlay-transition="vfm-fade"
        content-transition="vfm-fade"
        @update:modelValue="valueChanged"
    >
        <div class="relative w-full max-h-full">
            <div class="flex flex-col justify-between h-full">
                <div
                    class="flex items-center justify-between p-4 md:p-5"
                    :class="{'border-b rounded-t dark:border-gray-600': !fullscreen}"
                >
                    <slot name="header"/>
                    <UButton icon="close" variant="ghost" @click="emit('close')"/>
                </div>
                <div class="py-4 md:py-5 space-y-4">
                    <div class="w-full px-4 md:px-5 overflow-y-auto">
                        <slot/>
                    </div>
                </div>
                <div
                    class="flex items-center p-4 md:p-5" v-if="$slots.actions"
                    :class="{'border-gray-200 rounded-b dark:border-gray-600 border-t': !fullscreen}"
                >
                    <slot name="actions"/>
                </div>
            </div>
        </div>
    </VueFinalModal>
</template>

<style scoped>

</style>
