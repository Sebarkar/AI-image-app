<script setup lang="ts">
import {HFaceBookLogin} from '@healerlab/vue3-facebook-login';

const auth = useAuthStore()

const loading = ref(false)
const handleLoginSuccess = async (response) => {
    loading.value = true
    const {accessToken} = response.authResponse;
    return auth.login({
        provider: 'facebook',
        code: accessToken
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
    <HFaceBookLogin
        v-slot="fbLogin"
        app-id="969093084417500"
        @onSuccess="handleLoginSuccess"
        @onFailure="handleLoginError"
        scope="email,public_profile"
        fields="id,name,email,first_name,last_name,birthday"
    >
        <UButton
            :loading="loading"
            block
            size="xl"
            @click="fbLogin.initFBLogin()"
            icon="i-jam-facebook"
        >{{ $t('auth.text.Continue with facebook') }}
        </UButton>
    </HFaceBookLogin>
</template>

<style scoped>

</style>
