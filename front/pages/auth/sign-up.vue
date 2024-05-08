<script setup lang="ts">
definePageMeta({
    layout: false,
    middleware: ['sanctum:guest'],
})
</script>

<template>
    <div class="relative h-screen w-full">
        <div class="absolute inset-center">
            <div class="text-center mb-20">
                <h1 class="font-bold">Sign Up</h1>
            </div>
            <div class=" flex">
                <FormsAuthSignUpForm />
                <div>
                    image
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
