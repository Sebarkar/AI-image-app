import {defineStore} from 'pinia'
import {getCsrfToken} from "next-auth/react";

export const useAuthStore = defineStore('auth', () => {
    const auth = useSanctumAuth()
    const toast = useToast()
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
                toast.add({
                    color: 'success',
                    title: t('auth.text.User registered successfully.'),
                    icon: 'i-heroicons-check-circle'
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
                toast.add({color: 'success', title: t('auth.text.Verification email sent!'), icon: 'success'})
            })
    }

    const sendOneTimePassword = async (data) => {
        return await useApiFetch('auth/one-time-password', {body: data})
    }

    const login = async (data) => {
        await auth.login(data)
            .then(() => {
                toast.add({color: 'success', title: t('auth.text.You\'re signed in'), icon: 'success'})
            })
            .catch((error) => {
                if (error.data.message) {
                    toast.add({
                        color: 'error',
                        title: t('auth.text.Invalid credentials. Please try again.'),
                        icon: 'error'
                    })
                }
                throw error;
            })
    }

    const logout = async () => {
        return auth.logout()
            .then(() => {
                toast.add({color: 'success', title: t('auth.text.You\'re Logged Out'), icon: 'success'})
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
