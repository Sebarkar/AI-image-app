<!--Form Wrapper to Build form with Api Interface-->
<script setup lang="ts">

import {type InferType, number, object, string, boolean} from "yup";

const props = defineProps({
    fields: Object,
    endpoint: String,
})

const models = useModelsStore()
const datasets = useDatasetsStore()
const form = ref(null)
const loading = ref(false)

const schema = computed(() => {
    let obj = {};
    for (const [key, value] of Object.entries(props.fields)) {
        if (['string', 'textarea'].includes(value.type)) {
            obj[key] = string().nullable();
        } else if (['number'].includes(value.type)) {
            if (value.max && value.min) {
                obj[key] = number().positive().max(value.max).min(value.min);
            } else {
                obj[key] = number().nullable();
            }
        } else if (['boolean'].includes(value.type)) {
            obj[key] = boolean();
        }
        if (obj[key] && value.required === true) {
            obj[key] = obj[key].required();
        }
    }
    return object(obj)
})

const state = reactive({});

function getURL(image) {
    if (image) {
        return URL.createObjectURL(image)
    }
    return ''
}

watch(() => props.fields, (array) => {
    showMore.value = false;
    for (const [key, value] of Object.entries(props.fields)) {
        state[key] = value.value
    }
})

onMounted(() => {
    for (const [key, value] of Object.entries(props.fields)) {
        state[key] = value.value
    }
})

const emits = defineEmits({
    'saved': (state: any) => {
        return state
    },
    'loading': (loading: any) => {
        return loading
    },
})

watch(() => loading.value, (val) => {
    emits('loading', val)
})

async function saveForm() {
    loading.value = true
    const formData = fillFormData()
    await useApiFetch(props.endpoint, {
        method: 'POST',
        body: formData
    }).then((res) => {
        emits('saved', res)
        loading.value = false
    }).catch((e) => {
        loading.value = false
        if (e.data.errors) {
            form.value.setErrors(Object.keys(e.data.errors).map((name) => ({
                message: e.data.errors[name][0],
                path: name,
            })))
        }
    })
}

function fillFormData() {
    const data = new FormData();
    for (const [key, value] of Object.entries(state)) {
        data.append(key, value);
    }
    return data;
}

function addImage(image, fieldName) {
    state[fieldName] = image;
}

const onSubmit = () => {
    saveForm()
}

const submit = () => {
    validate().then(() => {
        saveForm()
    }).catch(() => {
    })
}

const onError = (error: any) => {
    console.log('error', error)
}

const showMore = ref(false)

async function validate() {
    return await form.value.validate()
}

function setPreset(preset) {
    for (const [key, value] of Object.entries(preset)) {
        state[key] = value
    }
}

defineExpose({
    validate,
    state,
    submit,
    loading
})

</script>

<template>
    <UForm ref="form" :state="state" class="space-y-5 w-full" @submit="onSubmit"
           :validateOn="['input', 'submit', 'change', 'blur']"
           :schema="schema"
           @error="onError"
    >
        <div v-for="(field, key) in fields" class="w-full">
            <UDivider
                v-if="field.type === 'separator'"
                :label="(showMore ? $t('input.text.Hide') : $t('input.text.Show')) + ' ' + field.description"
                type="dashed"
                @click="showMore = !showMore"
                class="cursor-pointer py-5"
            />
            <div v-if="!field.pro_field || (field.pro_field === true && showMore)" class="w-full">
                <UFormGroup :label="key" :name="key" v-if="field.type === 'select'">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <USelectMenu
                        size="xl"
                        v-model="state[key]"
                        :options="field.options"
                    />
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-if="field.type === 'setting_presets'">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <div class="flex flex-wrap gap-5 w-full h-44">
                        <div
                            v-for="(item, title) in field.options"
                            class="w-1/5 cursor-pointer"
                            @click="setPreset(item.presets); state[key] = title"
                        >
                            <div class="w-full pt-[100%] relative rounded-2xl border-2 overflow-hidden duration-150"
                            :class="{ ' border-success-600': title === state[key], ' border-transparent': title === state[key]}"
                            >
                                <ElementsImage :src="item.src" class="w-full h-full absolute top-0 left-0"/>
                            </div>
                            <div class="w-full text-center text-sm py-2 font-bold">
                                {{title}}
                            </div>
                        </div>
                    </div>
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'string'">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <UInput
                        v-model="state[key]"
                        :placeholder="field.placeholder"
                    />
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'file'" class="max-w-full">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <FormsInputsFileInput @input="addImage($event, key)" />
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'files'" class="max-w-full">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <FormsInputsMultipleFilesInput @input="state[key] = $event" />
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'boolean'" class="max-w-full">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <UToggle v-model="state[key]"/>
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'number' || field.type === 'integer' ">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <UTooltip text="Number" :shortcuts="['min:' + field.min, 'max:' + field.max]" class="mb-1"
                              :popper="{ arrow: true }">
                        <UInput
                            v-model="state[key]"
                            type="number"
                            :placeholder="field.placeholder"
                        />
                    </UTooltip>
                    <URange v-if="field.max" color="primary" placeholder="Search..." v-model="state[key]" :max="field.max" :min="field.min" :step="field.step" />
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'textarea'">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <UTextarea
                        autoresize
                        :placeholder="field.placeholder"
                        v-model="state[key]"
                    />
                    <span
                        v-if="field.show_token && models.selectedModel && datasets.selectedDataset && models.selectedModel.user_fine_tune === true && datasets.selectedDataset.token">
                    {{ $t('input.text.your dataset key is') }}: <b>{{ datasets.selectedDataset.token }}</b>
                </span>
                </UFormGroup>
            </div>
        </div>
        <slot name="actions"></slot>
    </UForm>
</template>

<style scoped>

</style>
