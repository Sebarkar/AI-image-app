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
    <div class="container mx-auto relative">
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
