<script setup lang="ts">
import type {Avatar} from '#ui/types'

const modals = useModalsStore()
const models = useModelsStore()
const form = ref(null)

const predict = computed(() => models.selectedModel?.predict)
const settings = computed(() => models.selectedModel?.predict_settings)

</script>

<template>
    <UCard class="w-full relative" :ui="{
            base: 'overflow-hidden h-full relative',
                background: 'bg-v1primary-950',
            header: {
                base: 'h-[6%] absolute top-0 w-full z-10',
                background: '!bg-v1primary-950/95 !backdrop-blur-sm ',
                padding: 'px-0 py-0 sm:px-0 !border-0',
            },
            divide: '',
            footer: {
                base: 'h-[6%]',
                background: 'bg-white',
                padding: 'px-0 py-0 sm:px-0 !border-0',
            },
            body: {
                background: 'bg-white',
                base: 'overflow-y-scroll h-[94%] !pt-[20%]',
            },
        }">
        <template #header>
            <PickersModelPicker class="w-full z-10 h-full"/>
        </template>
        <div class="flex flex-col gap-5 w-full ">
            <div class="grid items-end gap-5 justify-between w-full grid-cols-5">
                <div v-if="models.selectedModel && models.selectedModel.user_fine_tune"
                     class="grid items-end gap-5 justify-between w-full grid-cols-5 col-span-5">
                    <UAlert
                        v-if="models.selectedModel.completed"
                        class="col-span-5"
                        icon="i-heroicons-command-line"
                        color="primary"
                        variant="solid"
                        :title="$t('input.text.Ready')"
                        :description="$t('input.text.Selected Model already Fine-Tuned with your data. You can start generate images')"
                    />
                    <UAlert
                        v-else-if="models.selectedModel.processing"
                        class="col-span-5"
                        icon="loading"
                        color="primary"
                        variant="solid"
                        :title="$t('input.text.Fine-tune in process')"
                        :description="$t('input.text.This model use parent model data. Fine-Tune process will be finished in 5-30 minutes')"
                    />
                    <UAlert
                        v-else
                        class="col-span-5"
                        icon="i-heroicons-command-line"
                        color="gray"
                        :actions="[{ variant: 'solid', color: 'primary', click: () => modals.openModal('train-model'), label: $t('input.text.Start Fine-Tune Process') }]"
                        variant="solid"
                        :title="$t('input.text.Fine-tune the model')"
                        :description="$t('input.text.This model use parent model data. Fine-Tune model by you own data. It will take 5-30 minutes')"
                    />
                </div>
            </div>
            <FormsModelsModelForm
                ref="form"
                endpoint="predict"
                :settings="settings"
                :additional-data="{
                    model_name: models.selectedModel?.name,
                    model_owner: models.selectedModel?.owner,
                }"
                v-if="predict"
                :fields="predict"
            />
            <div v-else>
                <USkeleton class="w-full h-20 mb-5" v-for="id in 10"/>
            </div>
        </div>


        <template #footer>
            <div class="h-full w-full flex flex-center px-3">
                <UButton block @click="form.submit()" variant="solid" color="primary">Submit</UButton>
            </div>
        </template>
    </UCard>
</template>

<style scoped>

</style>
