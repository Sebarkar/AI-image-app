<script setup lang="ts">
import {object, string, ref as formRef, type InferType} from 'yup'
import type {FormSubmitEvent} from '#ui/types'

const auth = useAuthStore()
const router = useRouter()
const {t} = useI18n()

const schema = object({
    email: string().email(t('errors.invalid email')).required(),
    password: string().min(8).required(),
    confirmPassword: string().min(8).oneOf([formRef('password'), null], t('errors.password must match')).required(),
})

const form = ref()
type Schema = InferType<typeof schema>

const state = reactive({
    email: 'sebarkar@gmail.com',
    password: '123123123123',
    confirmPassword: '123123123123',
})

const errors = ref({})

async function onSubmit(event: FormSubmitEvent<Schema>) {
    await auth.registration(event.data)
        .then(async () => {
            return auth.login(event.data)
                .then(() => {
                    router.back()
                })
        })
        .catch((e) => {
            if (e.data.errors) {
                form.value.setErrors(Object.keys(e.data.errors).map((name) => ({
                    message: e.data.errors[name][0],
                    path: name,
                })))
            }
        })
}
</script>

<template>
    <UForm ref="form" :schema="schema" :state="state" class="space-y-4 min-w-[370px]" @submit="onSubmit" :validateOn="['input', 'submit']">
        <UFormGroup :label="$t('auth.text.Email')" name="email">
            <UInput v-model="state.email"/>
        </UFormGroup>

        <UFormGroup :label="$t('auth.text.Password')" name="password">
            <UInput v-model="state.password" type="new-password"/>
        </UFormGroup>

        <UFormGroup :label="$t('auth.text.Confirm Password')" name="confirmPassword">
            <UInput v-model="state.confirmPassword" type="new-password"/>
        </UFormGroup>

        <div class="flex">
            <UButton type="submit">
                {{ $t('auth.text.Sign Up') }}
            </UButton>
            <UButton variant="link" :to="localePath('/auth/sign-in')">
                {{ $t('auth.text.Already have an account') }}
            </UButton>
        </div>
    </UForm>
</template>

<style scoped>

</style>
