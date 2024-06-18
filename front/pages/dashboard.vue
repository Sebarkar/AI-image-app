<script setup lang="ts">
definePageMeta({
    layout: 'default',
    middleware: ['sanctum:auth'],
})
</script>

<template>
    <div class="flex w-full">
        <ModalsNewDatasetModal />
        <ModalsTrainModelModal />
        <SlidepanelsCreateModelPanel />
        <section>
            <TheSidebarMenu/>
        </section>
        <section class="flex-grow">
            <div class="bg-v1primary-950 pr-2 pb-2">
                <div class="bg-gray-100 rounded-lg min-h-screen">
                    <div class="w-full">
                        <AlertsEmailVerifiedRequired/>
                    </div>
                    <div class="max-w-full p-5">
                        <NuxtPage :page-key="route => route.fullPath"/>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>

</style>
