<!--Form Wrapper to Build form with Api Interface-->
<script setup lang="ts">

import {type InferType, number, object, string, boolean, array} from "yup";

const props = defineProps({
    fields: {
        type: Object,
        required: true,
    },
    endpoint: {
        type: String,
        required: true,
    },
    //Settings for the form construct
    settings: {
        type: Array,
    },
    //Add some data to the formData object
    additionalData: {
        type: Object,
    },
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
                obj[key] = number().positive().transform((value) => Number.isNaN(value) ? null : value).max(value.max).min(value.min);
            } else {
                obj[key] = number().transform((value) => Number.isNaN(value) ? null : value).nullable();
            }
        } else if (['boolean'].includes(value.type)) {
            obj[key] = boolean();
        } else if (['files'].includes(value.type)) {
            obj[key] = array().min(1);
        }
        if (obj[key] && value.required === true) {
            obj[key] = obj[key].required();
        }
    }
    return object(obj)
})

const state = reactive({});
const preparedFields = ref([]);

function clearDataForm() {
    for (let member in state) delete state[member];
    filesErrors.value = {}
    files.value = {}
    showMore.value = false;
}

function setState() {
    clearDataForm()
    preparedFields.value = props.fields
    for (const [key, value] of Object.entries(props.fields)) {
        state[key] = value.value
    }
    setSettings();
}

watch(() => props.fields, () => setState())
onMounted(() => setState())


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
            setErrors(e.data.errors)
        }
    })
}

function showAdditionalData(field) {
    if (!field.show_token) {
        return false;
    }
    if (models.selectedModel && datasets.selectedDataset && models.selectedModel.data) {
        return true;
    }
}

//filesErrors allows pass errors directly to the files inputs component
const filesErrors = ref({})

function setErrors(errors) {
    let errorsInFiles = {}
    form.value.setErrors(Object.keys(errors).map((name) => {
        let error;

        if (name.includes('.')) {
            if (!errorsInFiles.hasOwnProperty(name.split('.')[0])) {
                errorsInFiles[name.split('.')[0]] = [];
            }
            errorsInFiles[name.split('.')[0]].push({
                message: errors[name][0],
                path: name.split('.')[1],
            })
            error = {
                message: errors[name][0],
                path: name.split('.')[0],
            }
        } else {
            error = {
                message: errors[name][0],
                path: name,
            }
        }
        return error;
    }))

    filesErrors.value = Object.assign({}, errorsInFiles)
}

function removeError(fieldName) {
    form.value.setErrors(form.value.errors.filter(error => error.path !== fieldName));
}

function fillFormData() {
    const data = new FormData();
    for (const [key, value] of Object.entries(state)) {
        if (Array.isArray(value)) {
            value.forEach((item, index) => {
                data.append(`${key}[${index}]`, item);
            })
        } else {
            data.append(key, value);
        }
    }
    if (props.additionalData) {
        for (const [key, value] of Object.entries(props.additionalData)) {
            data.append(key, value);
        }
    }
    return data;
}

function addImage(fieldName, image) {
    state[fieldName] = image;
    removeError(fieldName);
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
const files = ref({})

async function validate() {
    return await form.value.validate()
}

function setPreset(preset) {
    for (const [key, value] of Object.entries(preset)) {
        state[key] = value
    }
}

function setSettings() {
    if (!props.settings) {
        return
    }
    for (const [key, value] of Object.entries(props.settings)) {
        if (key === 'pluck') {
            for (const [key1, value1] of Object.entries(value)) {
                preparedFields.value = Object.assign({[key1]: value1}, preparedFields.value);
            }
        }
    }
}

function addImages(field, images) {
    if (preparedFields.value[field]['type'] === 'files') {
        state[field] = images
    } else {
        preparedFields.value[field]['fields'].forEach((field, index) => {
            files.value[field].setFile(null)
            state[field] = ""
            if (images[index]) {
                files.value[field].setFile(images[index])
                state[field] = images[index]
            }
        })
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
        <div v-for="(field, key) in preparedFields" class="w-full">
            <UDivider
                v-if="field.type === 'separator'"
                :label="(showMore ? $t('input.text.Hide') : $t('input.text.Show')) + ' ' + field.description"
                type="dashed"
                @click="showMore = !showMore"
                class="cursor-pointer py-5"
            />
            <div v-if="!field.pro_field || (field.pro_field === true && showMore)" v-show="!field.hidden"
                 class="w-full">
                <UFormGroup :label="key" :name="key" v-if="field.as">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <FormsInputsMultipleFilesInput @input="addImages(key, $event)" v-if="field.as === 'images'"/>
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'select'">
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
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'setting_presets'">
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
                                {{ title }}
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
                    <FormsInputsFileInput @input="addImage(key, $event)" :ref="(el) => files[key] = el"/>
                </UFormGroup>
                <UFormGroup :label="key" :name="key" v-else-if="field.type === 'files'" class="max-w-full">
                    <template #description>
                        <UIcon name="info"/>
                        {{ field.description }}
                    </template>
                    <FormsInputsMultipleFilesInput @input="addImages(key, $event)" :errors="filesErrors[key]"/>
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
                    <URange v-if="field.max" color="primary" placeholder="Search..." v-model="state[key]"
                            :max="field.max" :min="field.min" :step="field.step"/>
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
                    <div
                        v-if="showAdditionalData(field)"
                    >
                        <span v-for="(data, name) in models.selectedModel.data" class="block">
                            {{ name }} : <b>{{ data }}</b>
                        </span>
                    </div>
                </UFormGroup>
            </div>
        </div>
        <slot name="actions"></slot>
    </UForm>
</template>

<style scoped>

</style>
