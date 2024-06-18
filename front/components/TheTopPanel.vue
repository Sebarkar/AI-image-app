<script setup lang="ts">
const modals = useModalsStore();
const auth = useAuthStore();

</script>

<template>
    <div class="bg-gray-950 p-5 relative">
        <div class="flex text-white justify-between items-center">
            <NuxtLinkLocale to="/">Header</NuxtLinkLocale>
            <div class="flex">
                <UButton
                    variant="link"
                    :to="localePath('/auth/sign-in')"
                    size="sm"
                    class="mr-2 uppercase" icon="signIn"
                    v-if="!auth.isAuthorized">
                    {{ $t('auth.text.Sign In') }}
                </UButton>
                <UButton :to="localePath('/auth/sign-up')" class="uppercase hidden" icon="signUp" v-if="!auth.isAuthorized">
                    {{ $t('auth.text.Sign Up') }}
                </UButton>
                <UButton @click="auth.logout()" class="uppercase" icon="signOut" v-if="auth.isAuthorized">
                    <span class="hidden">{{ $t('auth.text.Log Out') }}</span>
                </UButton>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
