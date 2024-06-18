<script setup lang="ts">
const auth = useAuthStore()

</script>

<template>
    <div class="py-2" v-if="auth.user && auth.user.email_verified_at === null">
        <UAlert
            :title="$t('auth.text.Verification email is required!')"
            color="error"
            icon="error"
            :actions="[{ variant: 'solid', color: 'primary', label: $t('auth.text.Send verification email'), click: () => auth.sendEmailVerification() }]"
        />
    </div>
</template>

<style scoped>

</style>
