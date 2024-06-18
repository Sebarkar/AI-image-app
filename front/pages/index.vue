<script setup lang="ts">
definePageMeta({
    layout: 'default',
})

const tasks = useTasksStore()
const sendRequest = async () => {
    await useApiFetch('test')
}

const send404Request = async () => {
    const data = await useApiFetch('tes212t')
    tasks.addTask(data)
}

const settings = reactive({
    imagesSrc: [], //Image for source
    imagesStyle: [], //Image for take style
    aspectRatio: '16:9', //Changing the aspect ratio.
    chaos: '30', //The higher the chaos the more unusual and unexpected results.
    imageWeight: '30', //Sets image prompt weight relative to text weight. The default value is 1.
    no: 'moon roses', //Exclude specific object in the image.
    quality: 0.5,
    repeat: 40,
    seed: 1000, //The Midjourney bot uses a seed number to create a field of visual noise, like television static, as a starting point to generate the initial image grids.
    stop: 35, //The Midjourney bot uses a seed number to create a field of visual noise, like television static, as a starting point to generate the initial image grids.
    style: 'cute',
    stylize: 5,  //Influences how strongly Midjourney's default aesthetic style is applied
    tile: true,  //Generates images that can be used as repeating tiles to create seamless patterns.
    weird: 1000,  //Explore unusual aesthetics with the experimental weird parameter
})

</script>

<template>
    <div class="relative pt-10 flex flex-col gap-y-20">
        <div class="min-h-[80vh] px-5 pb-10 flex items-center justify-between relative">
            <div class="max-w-xl flex flex-col gap-10 justify-end">
                <h1 class="text-7xl">Lorem ipsum dolor sit amet. </h1>
                <span class="text-xl">Cum dolore esse eveniet fuga illo impedit in laudantium neque odio perferendis quas sequi similique sit velit voluptatum!</span>
                <div class="flex gap-x-3">
                    <UButton class="uppercase font-bold tracking-widest">Try it now</UButton>
                </div>
            </div>
            <div class="absolute right-0 inset-y-center w-1/2 bg-primary min-h-full"></div>
        </div>

        <div class="flex gap-x-5 flex-wrap justify-between">
            <h2 class="w-full text-5xl py-10 text-center">Nostrum perferendis voluptatum?</h2>
            <div class="w-1/4 min-h-60 h-full bg-red-100 rounded-2xl flex flex-center" v-for="i in 3">
                <div>Lorem {{i}}</div>
            </div>
        </div>

        <h1>Index</h1>

        <div class="sticky top-0 p-4 bg-white">
            <UButton size="xl" class="mr-2" @click="sendRequest" color="success">send request</UButton>
            <UButton size="xl" @click="send404Request">send 404 request</UButton>
        </div>
        <div v-for="task in tasks.tasks">
            {{ task.id }}
            <div class="flex " v-if="task.result">
                <ElementsImage
                    v-for="link in task.result"
                    :src="link"
                    class="w-1/4 rounded-xl p-5"
                />
            </div>
            <div class="flex" v-else>
                <div class="p-5 w-1/4" v-for="i in 4">
                    <div class=" rounded-xl h-80 bg-slate-300 animate-pulse">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
