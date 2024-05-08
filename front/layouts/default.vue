<script setup lang="ts">
const auth = useAuthStore()
const toast = useToast()

</script>

<template>
    <div>
        <div class="py-2" v-if="auth.user && auth.user.email_verified_at === null">
            <UAlert
                :title="$t('auth.text.Verification email is required!')"
                color="error"
                icon="error"
                :actions="[{ variant: 'solid', color: 'primary', label: $t('auth.text.Send verification email'), click: () => auth.sendEmailVerification() }]"
            />
        </div>
        <div class="w-full flex flex-col">
            <slot/>
        </div>
    </div>
</template>

<style scoped>

</style>
