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
const showForm = ref(false)

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
    <div class="flex flex-center flex-col">
        <div class="flex flex-col bg-white md:px-10 px-2 pt-10 rounded-xl mb-5 pb-5">
            <div class="text-center mb-20">
                <h1 class="font-bold">{{ $t('auth.text.Sign Up') }}</h1>
            </div>
            <div class="mb-10 space-y-4 min-w-[280px]">
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

            <UForm ref="form" :schema="schema" :state="state" class="space-y-4 min-w-[280px]" @submit="onSubmit"
                   :validateOn="['input', 'submit']" v-show="showForm">
                <UFormGroup :label="$t('auth.text.Email')" name="email">
                    <UInput v-model="state.email"/>
                </UFormGroup>

                <UFormGroup :label="$t('auth.text.Password')" name="password">
                    <UInput v-model="state.password" type="new-password"/>
                </UFormGroup>

                <UFormGroup :label="$t('auth.text.Confirm Password')" name="confirmPassword">
                    <UInput v-model="state.confirmPassword" type="new-password"/>
                </UFormGroup>

                <UButton type="submit" block>
                    {{ $t('auth.text.Sign Up') }}
                </UButton>
            </UForm>
        </div>

        <UButton variant="link" :to="localePath('/auth/sign-in')">
            {{ $t('auth.text.Already have an account') }}
        </UButton>
    </div>
</template>

<style scoped>

</style>
