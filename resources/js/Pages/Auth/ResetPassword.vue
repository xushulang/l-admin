<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NAutoComplete, NButton, NForm, NFormItem, NInput } from 'naive-ui'
import { computed, useTemplateRef } from 'vue'

defineOptions({ layout: GuestLayout })

const props = defineProps<{
    email: string
    token: string
}>()

const formRef = useTemplateRef<FormInst | null>('formRef')

const model = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const rules: FormRules = {
    phone: [
        {
            key: 'phone',
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Phone') }),
            trigger: ['input', 'change'],
        },
        {
            validator: (rule, value) => {
                return /^\+?\(?\d{3}\)?[-\s.]?\d{3}[-\s.]?\d{4,6}$/.test(value)
            },
            renderMessage: () => trans('validation.regex', { Attribute: trans('Phone') }),
            trigger: ['input', 'change'],
        },
    ],
    email: [
        {
            type: 'email',
            renderMessage: () => trans('Please enter :name', { name: trans('Email') }),
            trigger: ['input', 'change'],
        },
    ],
    password: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Password') }),
            trigger: ['input', 'change'],
        },
    ],
    password_confirmation: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please confirm :name', { name: trans('Confirm Password') }),
            trigger: ['input', 'change'],
        },
        {
            validator: (rule, value) => {
                return value === model.password
            },
            renderMessage: () => trans('validation.confirmed', { Attribute: trans('Password') }),
            trigger: ['input', 'change'],
        },
    ],
    code: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Code'),
            trigger: ['input', 'change'],
        },
    ],
}

const autoCompleteOptions = computed(() => {
    return ['@outlook.com', '@163.com', '@qq.com'].map((suffix) => {
        const prefix = model.email.split('@')[0]
        return {
            label: prefix + suffix,
            value: prefix + suffix,
        }
    })
})

function submit(e: Event) {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            model.post(route('password.store'), {
                onFinish: () => {
                    model.password = ''
                    model.password_confirmation = ''
                },
            })
        }
    })
}
</script>

<template>
    <div>
        <Head :title="$t('Reset Password')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
                <NForm
                    ref="formRef"
                    :model
                    :rules
                    label-placement="top"
                    require-mark-placement="right-hanging"
                    :label-width="160"
                    :disabled="model.processing"
                    @submit.prevent="submit"
                >
                    <NFormItem first :label="$t('Email')" path="email">
                        <NAutoComplete
                            v-model:value="model.email"
                            :placeholder="$t('Email')"
                            :options="autoCompleteOptions"
                            :input-props="{ autocomplete: 'email' }"
                        />
                    </NFormItem>

                    <NFormItem first :label="$t('Password')" path="password">
                        <NInput
                            v-model:value="model.password"
                            type="password"
                            :placeholder="$t('Password')"
                            :input-props="{ autocomplete: 'new-password' }"
                        />
                    </NFormItem>

                    <NFormItem first :label="$t('Confirm Password')" path="password_confirmation">
                        <NInput
                            v-model:value="model.password_confirmation"
                            :placeholder="$t('Confirm Password')"
                            :disabled="!model.password"
                            type="password"
                            :input-props="{ autocomplete: 'new-password' }"
                        />
                    </NFormItem>

                    <NFormItem :show-label="false" :show-feedback="false" class="justify-items-end">
                        <div class="flex items-center gap-2">
                            <NButton type="primary" attr-type="submit" class="px-4">
                                {{ $t('Reset Password') }}
                            </NButton>
                        </div>
                    </NFormItem>
                </NForm>
            </div>
        </div>
    </div>
</template>
