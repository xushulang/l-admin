<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NAutoComplete, NButton, NCard, NCountdown, NForm, NFormItem, NInput, useMessage } from 'naive-ui'
import { computed, ref, useTemplateRef } from 'vue'

defineOptions({ layout: GuestLayout })

const message = useMessage()

const formRef = useTemplateRef<FormInst | null>('formRef')

const model = useForm({
    username: '',
    name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    code: '',
})

const rules: FormRules = {
    username: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Username') }),
            trigger: ['input', 'change'],
        },
    ],
    phone: [
        {
            key: 'phone',
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Phone') }),
            trigger: ['input', 'change'],
        },
        {
            pattern: /^\+?\(?\d{3}\)?[-\s.]?\d{3}[-\s.]?\d{4,6}$/,
            renderMessage: () => trans('validation.regex', { Attribute: trans('Phone') }),
            trigger: ['input', 'change'],
        },
    ],
    name: [
        {
            type: 'string',
            renderMessage: () => trans('Please enter :name', { name: trans('Name') }),
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
        {
            min: 8,
            message: trans('validation.min.string', { Attribute: trans('Password'), min: '8' }),
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
            min: 8,
            message: trans('validation.min.string', { Attribute: trans('Confirm Password'), min: '8' }),
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
            renderMessage: () => trans('Please enter :name', { name: trans('Code') }),
            trigger: ['input', 'change'],
        },
    ],
}

const smsSent = ref(false)

const autoCompleteOptions = computed(() => {
    return ['@outlook.com', '@163.com', '@qq.com'].map((suffix) => {
        const prefix = model.email.split('@')[0]
        return {
            label: prefix + suffix,
            value: prefix + suffix,
        }
    })
})

function renderCountdown({ seconds }: { seconds: number }): string {
    return `${String(seconds).padStart(2, '0')}秒后可重新发送`
}

function getCode(): void {
    formRef.value?.validate(
        (errors) => {
            if (!errors) {
                axios.post(route('verification.code.phone'), { phone: model.phone }).then((response) => {
                    if (response.data.status === 'success') {
                        message.success(response.data.message)
                        smsSent.value = true
                    }
                    else {
                        message.error(response.data.message)
                    }
                })
            }
        },
        (rule) => {
            return rule?.key === 'phone'
        },
    )
}

function submit(e: Event): void {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            model.post(route('register'), {
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
        <Head :title="$t('Register')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center items-center gap-3">
                <ApplicationLogo full class="w-20 h-20 fill-current text-gray-500" />

                <NCard class="shadow-sm">
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
                        <NFormItem first :label="$t('Username')" path="username">
                            <NInput
                                v-model:value="model.username"
                                :placeholder="$t('Please enter :name', { name: $t('Username') })"
                                autofocus
                            />
                        </NFormItem>

                        <NFormItem first :label="$t('Phone')" path="phone">
                            <NInput
                                v-model:value="model.phone"
                                :placeholder="$t('Please enter :name', { name: $t('Phone') })"
                                :input-props="{ autocomplete: 'tel' }"
                            />
                        </NFormItem>

                        <NFormItem first :label="$t('SMS Verification Code')" path="code">
                            <div class="w-full flex justify-between gap-2">
                                <NInput
                                    v-model:value="model.code"
                                    type="text"
                                    :placeholder="$t('Please enter :name', { name: $t('Code') })"
                                />
                                <NButton v-if="!smsSent" type="info" @click="getCode">
                                    {{ $t('Send Verification Code') }}
                                </NButton>
                                <NButton v-else type="error">
                                    <NCountdown :render="renderCountdown" :duration="59000" @finish="smsSent = false" />
                                </NButton>
                            </div>
                        </NFormItem>

                        <NFormItem first :label="$t('Name')" path="name">
                            <NInput
                                v-model:value="model.name"
                                :placeholder="$t('Please enter :name', { name: $t('Name') })"
                                :input-props="{ autocomplete: 'name' }"
                            />
                        </NFormItem>

                        <NFormItem first :label="$t('Email')" path="email">
                            <NAutoComplete
                                v-model:value="model.email"
                                :placeholder="$t('Please enter :name', { name: $t('Email') })"
                                :options="autoCompleteOptions"
                                :input-props="{ autocomplete: 'email' }"
                            />
                        </NFormItem>

                        <NFormItem first :label="$t('Password')" path="password">
                            <NInput
                                v-model:value="model.password"
                                type="password"
                                :placeholder="$t('Please enter :name', { name: $t('Password') })"
                                :input-props="{ autocomplete: 'new-password' }"
                            />
                        </NFormItem>

                        <NFormItem first :label="$t('Confirm Password')" path="password_confirmation">
                            <NInput
                                v-model:value="model.password_confirmation"
                                :placeholder="$t('Please enter :name', { name: $t('Confirm Password') })"
                                :disabled="!model.password"
                                type="password"
                                :input-props="{ autocomplete: 'new-password' }"
                            />
                        </NFormItem>

                        <NFormItem :show-label="false" :show-feedback="false" class="justify-items-end">
                            <div class="flex items-center gap-2">
                                <Link :href="route('login')" class="hover:text-blue-500">
                                    {{ $t('Already registered?') }}
                                </Link>

                                <NButton type="primary" attr-type="submit" class="px-4">
                                    {{ $t('Register') }}
                                </NButton>
                            </div>
                        </NFormItem>
                    </NForm>
                </NCard>
            </div>
        </div>
    </div>
</template>
