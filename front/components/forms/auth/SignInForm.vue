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

const showForm = ref(false)
</script>

<template>
    <div class="flex flex-center flex-col">
        <div class="flex flex-col bg-white md:px-10 px-2 pt-10 rounded-xl mb-5 pb-5">
            <div class="text-center mb-20">
                <h1 class="font-bold">{{ $t('auth.text.Sign In') }}</h1>
            </div>
            <div class="min-w-[280px]">
                <div class="mb-10 space-y-4">
                    <ElementsGoogleSignInButton/>
                    <ElementsFaceBookSignInButton/>
                </div>
                <UDivider @click="showForm = !showForm" class="mb-10 underline cursor-pointer flex flex-center">
                    <div class="flex flex-center">
                        {{ $t('auth.text.Continue with email') }}
                        <UIcon
                            name="i-ic-sharp-keyboard-arrow-down"
                            :class="{ 'transform rotate-180': showForm}"
                        ></UIcon>
                    </div>
                </UDivider>
                <UForm v-show="showForm" :schema="schema" :state="state" class="space-y-4 min-w-[280px]"
                       @submit="onSubmit"
                       :validateOn="['input', 'submit']">
                    <UFormGroup :label="$t('auth.text.Email')" name="email">
                        <UInput v-model="state.email"/>
                    </UFormGroup>

                    <UFormGroup :label="$t('auth.text.Password')" name="password">
                        <UInput v-model="state.password" type="new-password"/>
                    </UFormGroup>

                    <UButton block type="submit" :loading="loading">
                        {{ $t('auth.text.Sign In') }}
                    </UButton>
                    <UButton block variant="link" @click="modals.closeModal('auth')"
                             :to="localePath('/auth/one-time-sign-in')">
                        {{ $t('auth.text.Forgot password') }}
                    </UButton>
                </UForm>
            </div>
        </div>
        <UButton variant="link" :to="localePath('/auth/sign-up')">
            {{ $t('auth.text.Don\'t have an account yet') }}
        </UButton>
    </div>
</template>

<style scoped>

</style>
