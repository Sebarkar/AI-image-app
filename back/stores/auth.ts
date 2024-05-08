import {defineStore} from 'pinia'
import {getCsrfToken} from "next-auth/react";

export const useAuthStore = defineStore('auth', () => {
    const auth = useSanctumAuth()
    const snackbar = useSnackbarStore();
    const {t} = useI18n()
    interface MyCustomUser {
        id: number;
        email: string;
        email_verified_at: string | Date | null;
    }

    const user = auth.user.value as MyCustomUser | null

    const isInitialRequestFinished = ref(false)

    const isAuthorized = computed(() => {
        return auth.isAuthenticated.value
    })

    const registration = async (data) => {
        return await useApiFetch('auth/register', {body: data})
            .then((data) => {
                snackbar.add({
                    color: 'success',
                    text: t('auth.text.User registered successfully.'),
                })
                return data;
            })
            .catch((error) => {
                throw error;
            })
    }

    const sendEmailVerification = async () => {
        return await useApiFetch('auth/email/verification-notification')
            .then(() => {
                snackbar.add({color: 'success', text: t('auth.text.Verification email sent!')})
            })
    }

    const sendOneTimePassword = async (data) => {
        return await useApiFetch('auth/one-time-password', {body: data})
    }

    const login = async (data) => {
        await auth.login(data)
            .then(() => {
                snackbar.add({color: 'success', text: t('auth.text.You\'re signed in')})
            })
            .catch((error) => {
                if (error.data.message) {
                    snackbar.add({
                        color: 'error',
                        text: t('auth.text.Invalid credentials. Please try again.'),
                    })
                }
                throw error;
            })
    }

    const logout = async () => {
        return auth.logout()
            .then(() => {
                snackbar.add({color: 'success', text: t('auth.text.You\'re Logged Out')})
            })
    }

    return {
        user,
        isAuthorized,
        registration,
        sendOneTimePassword,
        logout,
        login,
        sendEmailVerification
    };
})
