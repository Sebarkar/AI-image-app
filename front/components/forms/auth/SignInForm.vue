<script setup lang="ts">
import {object, string, type InferType} from 'yup'
import type {FormSubmitEvent} from '#ui/types'

const auth = useAuthStore()
const modals = useModalsStore()

const schema = object({
    email: string().email().required(),
    password: string().min(8).required()
})

type Schema = InferType<typeof schema>

const state = reactive({
    email: undefined,
    password: undefined,
    confirmPassword: undefined,
})

const invalidCredentials = ref(false)
const loading = ref(false)

async function onSubmit(event: FormSubmitEvent<Schema>) {
    // Do something with event.data
    invalidCredentials.value = false
    loading.value = true
    await auth.login(event.data)
        .then(() => {
            loading.value = false
            modals.closeModal('auth')
        })
        .catch((error) => {
            loading.value = false
        })
}
</script>

<template>
    <UForm :schema="schema" :state="state" class="space-y-4 min-w-[370px]" @submit="onSubmit" :validateOn="['input', 'submit']">
        <UFormGroup :label="$t('auth.text.Email')" name="email">
            <UInput v-model="state.email"/>
        </UFormGroup>

        <UFormGroup :label="$t('auth.text.Password')" name="password">
            <UInput v-model="state.password" type="new-password"/>
        </UFormGroup>

        <div class="flex">
            <UButton type="submit" :loading="loading">
                {{ $t('auth.text.Sign In') }}
            </UButton>
            <UButton variant="link" @click="modals.closeModal('auth')" :to="localePath('/auth/sign-up')">
                {{ $t('auth.text.Don\'t have an account yet') }}
            </UButton>
        </div>
        <div>
            <UButton variant="link" @click="modals.closeModal('auth')" :to="localePath('/auth/one-time-sign-in')">
                {{ $t('auth.text.Forgot password') }}
            </UButton>
        </div>
    </UForm>
</template>

<style scoped>

</style>
