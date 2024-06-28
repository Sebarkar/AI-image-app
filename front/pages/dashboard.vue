<script setup lang="ts">
definePageMeta({
    layout: 'default',
    middleware: ['sanctum:auth'],
})
</script>

<template>
    <div class="flex w-full h-[calc(100svh-84px)] overflow-hidden">
        <ModalsNewDatasetModal />
        <ModalsTrainModelModal />
        <ModalsImageViewerModal />
        <SlidepanelsCreateModelPanel />
        <section>
            <TheSidebarMenu/>
        </section>
        <section class="flex-grow h-full">
            <div class="bg-v1primary-950 pr-2 pb-2 h-full">
                <div class="bg-gray-100 rounded-lg h-full">
                    <div class="w-full">
                        <AlertsEmailVerifiedRequired/>
                    </div>
                    <div class="max-w-full p-5 h-full">
                        <NuxtPage :page-key="route => route.fullPath"/>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>

</style>
