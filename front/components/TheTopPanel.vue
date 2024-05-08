<script setup lang="ts">
const modals = useModalsStore();
const auth = useAuthStore();

</script>

<template>
    <div class="bg-indigo-800 p-5 relative">
        <div class="flex text-white justify-between items-center">
            <NuxtLinkLocale to="/">Header</NuxtLinkLocale>
            <div class="absolute inset-center">middle</div>
            <div class="flex">
                <UButton @click="modals.openModal('auth')" class="mr-2 uppercase" icon="signIn"
                         v-if="!auth.isAuthorized">{{ $t('auth.text.Sign In') }}
                </UButton>
                <UButton :to="localePath('/auth/sign-up')" class="uppercase" icon="signUp"
                         v-if="!auth.isAuthorized">{{ $t('auth.text.Sign Up') }}
                </UButton>
                <UButton @click="auth.logout()" class="uppercase" icon="signOut" v-if="auth.isAuthorized">
                    {{ $t('auth.text.Log Out') }}
                </UButton>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
