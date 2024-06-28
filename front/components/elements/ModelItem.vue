<script setup lang="ts">
defineProps({
    model: {
        type: Object,
        required: true
    },
    variant: {
        type: String,
        default: 'square'
    },
    selected: {
        type: Boolean,
        default: false
    }
})

const emits = defineEmits({
    select: (model: any) => true
})
</script>

<template>
    <div
        class="relative group cursor-pointer"
        :class="{
            'pb-[100%] flex flex-center mb-20 ': variant === 'square',
            'grid grid-cols-3': variant === 'rectangle',
        }"
    >
        <div class="absolute z-10 flex flex-col gap-1"
             :class="{
                'top-0 left-0': variant === 'square',
                'inset-y-center right-5': variant === 'rectangle',
            }"
        >
            <UPopover mode="hover" class="flex flex-center" v-if="model.description">
                <UIcon class="w-6 h-6 p-1 flex flex-center rounded-full bg-white text-v1primary-950" name="info"/>
                <template #panel>
                    <div class="p-2 max-w-52 text-sm">
                        {{ model.description }}
                    </div>
                </template>
            </UPopover>
            <UIcon class="z-10 w-6 h-6 p-1 bg-success-700 rounded-full" v-if="selected" name="success" color="white"/>
        </div>
        <div
            class="h-full duration-300 border-4 rounded-2xl overflow-hidden "
            :class="{
            'border-success-700 scale-110': selected,
            'group-hover:scale-110 border-transparent': !selected,
            'absolute top-0 left-0 w-full': variant === 'square',
            'w-[50%] aspect-square': variant === 'rectangle',
        }"
            @click="emits('select', model)"
        >
            <ElementsImage :src="model.image" />
        </div>
        <div
            class="flex flex-center duration-500 rounded-2xl cursor-pointer flex-col"
            :class="{
                'absolute top-[110%] w-full max-h-10 inset-x-center ': variant === 'square',
                'cols-span-2': variant === 'rectangle',
            }"
        >
            <span
                class="uppercase font-bold break-words group-hover:text-white/75 duration-150 text-2xs w-full px-2 text-white">
                {{ model.owner }}
            </span>
            <span
                class="uppercase font-bold break-words group-hover:text-white/75 duration-150 text-xs w-full px-2 text-white">
                {{ model.name }}
            </span>
        </div>
    </div>
</template>

<style scoped>

</style>
