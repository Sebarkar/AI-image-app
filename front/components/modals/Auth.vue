<script setup lang="ts">
const modals = useModalsStore();

defineProps({
    value: Boolean
})
</script>

<template>
    <UModal :model-value="value">
        <div class="p-4">
            <div class="flex flex-col">
                <h2 class="text-2xl font-bold text-center w-full">{{ $t('auth.text.Sign In') }}</h2>
                <div>
                    <FormsAuthSignInForm />
                </div>
            </div>
            <div class="mt-4">
                <UButton class="w-full" block variant="outline" @click="modals.closeModal('auth')">{{ $t('modal.text.Close') }}</UButton>
            </div>
        </div>
    </UModal>
</template>

<style scoped>

</style>
