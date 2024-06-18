<script setup lang="ts">
const auth = useAuthStore()
const loading = ref(false)
const handleLoginSuccess = async (response) => {
    loading.value = true
    const {access_token} = response;
    return auth.login({
        provider: 'google',
        code: access_token
    })
        .then(() => {
            loading.value = false
        })
        .catch(() => {
            loading.value = false
        })
};
const handleLoginError = () => {
    console.error("Login failed");
};
</script>

<template>
        <GoogleLogin
            :callback="handleLoginSuccess"
            popup-type="TOKEN"
            class="w-full"
        >
            <UButton
                :loading="loading"
                block
                size="xl"
                icon="i-jam-google"
            >{{ $t('auth.text.Continue with google') }}
            </UButton>
        </GoogleLogin>
</template>

<style scoped>

</style>
