<script setup lang="ts">
import {object, string, type InferType} from 'yup'
import type {FormSubmitEvent} from '#ui/types'

const auth = useAuthStore()
const modals = useModalsStore()

const {t} = useI18n()

const emailSchema = object({
    email: string().email(t('errors.invalid email')).required(),
})

const codeSchema = object({
    oneTimeCode: string().min(8).max(8).required(),
})

type emailSchema = InferType<typeof emailSchema>
type codeSchema = InferType<typeof codeSchema>

const codeForm = ref()
const emailForm = ref()

const loading = ref(false);

const state = reactive({
    email: undefined,
    oneTimeCode: undefined,
})

const isEmailSent = ref(false);

const errors = ref({})

async function sendEmail() {
    loading.value = true;
    auth.sendOneTimePassword(state)
        .then((data) => {
            if (data.errors) {
                emailForm.value.setErrors(Object.keys(data.errors).map((name) => ({
                    message: data.errors[name][0],
                    path: name,
                })))
            } else {
                isEmailSent.value = true;
            }
            loading.value = false;
        })
}

async function login() {
    loading.value = true;
    auth.login(state)
        .then((data) => {
            console.log(data)
            loading.value = false;
            modals.closeModal('auth')
        })
        .catch((error) => {
            codeForm.value.setErrors(Object.keys(error.data.errors).map((name) => ({
                message: error.data.errors[name][0],
                path: name,
            })))
            loading.value = false;
        })
}

</script>

<template>
    <div class="min-w-[280px] mb-10">
        <UAlert
            v-if="isEmailSent"
            class="mb-5"
            :actions="[{ variant: 'outline', color: 'white', label: $t('auth.text.change email'), 'click': () => isEmailSent = false}]"
            icon="i-heroicons-command-line"
            color="primary"
            variant="solid"
            :title="$t('auth.text.We already sent email with confirmation code to your email. Please check your email and enter the confirmation code below.')"
            :description="$t('auth.text.Email used: ') + state.email"
        />
        <UForm ref="emailForm" :schema="emailSchema" :state="state" class="space-y-4 " @submit="sendEmail" :validateOn="['input', 'submit']" v-if="!isEmailSent">
            <UFormGroup :label="$t('auth.text.Email')" name="email">
                <UInput v-model="state.email"/>
            </UFormGroup>
            <div class="flex">
                <UButton type="submit" :loading="loading" block>
                    {{ $t('auth.text.Get one time password') }}
                </UButton>
            </div>
        </UForm>
        <UForm ref="codeForm" :schema="codeSchema" :state="state" class="space-y-4 " @submit="login" :validateOn="['input', 'submit']" v-else>
            <UFormGroup :label="$t('auth.text.Confirmation Code')" name="oneTimeCode">
                <UInput v-model="state.oneTimeCode"/>
            </UFormGroup>
            <div class="flex">
                <UButton type="submit" :loading="loading" block>
                    {{ $t('auth.text.Confirm Code') }}
                </UButton>
            </div>
        </UForm>
    </div>
</template>

<style scoped>

</style>
