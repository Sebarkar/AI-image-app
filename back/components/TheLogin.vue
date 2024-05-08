<script setup lang="ts">
const loginDetails = ref({
    email: '',
    password: '',
})

const snackbar = useSnackbarStore();

const loginForm = ref(null);

const route = useRoute();

const auth = useAuthStore()

const loggedOut = computed(() => {
    return !auth.isAuthorized;
})

const {t} = useI18n()

const form = ref(false)
const loading = ref(false)

const passwordRules = [
    v => !!v || t('error.password is required'),
    v => (v.length >= 8) || t('error.password must be more than 8 characters')
]

const emailRules = [
    v => !!v || t('error.email is required'),
    v => /.+@.+/.test(v) || t('error.email must be valid')
]

const login = async (credentials = null) => {
    loading.value = true;
    try {
        await auth.login(credentials.email ? credentials : loginDetails.value, {callbackUrl: route.fullPath})
            .then(() => {
                snackbar.add({color: 'green', text: t('snackbar.login success')})
                loading.value = false;
                modal.value = false;
            })
            .catch(() => {
                snackbar.add({color: 'red', text: t('snackbar.login error')})
                loading.value = false;
            })

    } catch (error) {
        loading.value = false;
    }
}

onMounted(() => {
    modal.value = loggedOut.value;
})

const logout = () => {
    auth.logout()
    snackbar.add({color: 'green', text: t('snackbar.logout success')})
}

const modal = ref(false)

const loginWithTest = () => {
    login({
        email: 'owner@owner.com',
        password: 'owner12345'
    });
}

</script>

<template>
    <v-card class="elevation-12" max-width="600px" width="100%" max-height="300px">
        <v-toolbar color="secondary" dark>
            <v-toolbar-title>{{ $t('auth.login form') }}</v-toolbar-title>
            <v-spacer></v-spacer>
        </v-toolbar>
        <v-card-text>
            <v-form
                id="loginForm"
                ref="loginForm"
                validate-on="input"
                v-model="form"
                method="post"
                @submit.prevent="login"
            >
                <v-text-field
                    id="email"
                    v-model="loginDetails.email"
                    :label="t('auth.email')"
                    prepend-icon="$user"
                    :rules="emailRules"
                    autocomplete="email"
                    name="email"
                    type="text"></v-text-field>
                <v-text-field
                    id="password"
                    v-model="loginDetails.password"
                    :label="t('auth.password')"
                    prepend-icon="$password"
                    :rules="passwordRules"
                    autocomplete="password"
                    name="password"
                    type="password"></v-text-field>
                <v-divider></v-divider>
            </v-form>
        </v-card-text>
        <v-card-actions>
            <v-btn
                :loading="loading"
                color="secondary"
                form="loginForm"
                @click="loginWithTest"
            >{{ $t('auth.demo version') }}
            </v-btn>
            <v-spacer></v-spacer>
            <v-btn
                :disabled="!form"
                :loading="loading"
                color="secondary"
                form="loginForm"
                type="submit"
            >{{ $t('auth.login') }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<style scoped>
.sad {
}

</style>
