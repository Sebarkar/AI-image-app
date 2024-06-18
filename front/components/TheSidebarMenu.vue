<script setup lang="ts">
const route = useRoute()
const {t} = useI18n()
const localePath = useLocalePath()

const menu = [
    [
        [
            {
                icon: 'i-heroicons-home',
                to: localePath('/dashboard')
            },
            {
                icon: 'i-heroicons-chart-bar',
                to: localePath('/dashboard/datasets')
            },
            {
                icon: 'i-heroicons-command-line',
                to: '/components/command-palette'
            }
        ],
        [
            {
                icon: 'i-heroicons-home',
                to: '/getting-started/installation'
            },
            {
                icon: 'i-heroicons-chart-bar',
                to: `${route.path.startsWith('/dev') ? '/dev' : ''}/components/vertical-navigation`
            },
            {
                icon: 'i-heroicons-command-line',
                to: '/components/command-palette'
            }
        ],
        [
            {
                icon: 'i-heroicons-light-bulb'
            },
            {
                icon: 'i-heroicons-question-mark-circle'
            }
        ]
    ],
]
</script>

<template>
    <div class="w-full h-full">
        <div v-for="(parts, id) in menu" class="w-full h-full bg-v1primary-950 px-2 min-h-screen pb-10">
            <UVerticalNavigation
                class="sticky top-0"
                :ui="{
                    size: 'text-lg',
                    icon: {
                        base: 'flex-shrink-0 w-10 h-10 relative',
                        active: 'text-gray-700 dark:text-gray-200',
                        inactive: 'text-gray-400 dark:text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-200',
                    },
                }"
                :links="parts"
                :key="id"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
