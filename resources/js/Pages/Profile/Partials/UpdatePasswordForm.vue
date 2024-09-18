<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import { useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NButton, NCard, NForm, NFormItem, NInput } from 'naive-ui'
import { useTemplateRef } from 'vue'

const passwordInput = useTemplateRef<HTMLInputElement | null>('passwordInput')
const currentPasswordInput = useTemplateRef<HTMLInputElement | null>('currentPasswordInput')

const formRef = useTemplateRef<FormInst | null>('formRef')

const model = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const rules: FormRules = {
    current_password: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Current Password') }),
            trigger: ['input', 'change'],
        },
    ],
    password: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('New Password') }),
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
            renderMessage: () => trans('Please enter :name', { name: trans('Confirm Password') }),
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
}

function submit(e: Event) {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            model.put(route('password.update'), {
                onSuccess: () => {
                    model.current_password = ''
                    model.password = ''
                    model.password_confirmation = ''
                },
                onError: (errors) => {
                    if (errors.password) {
                        model.password = ''
                        model.password_confirmation = ''

                        passwordInput.value?.focus()
                    }

                    if (errors.current_password) {
                        model.current_password = ''

                        currentPasswordInput.value?.focus()
                    }
                },
            })
        }
    })
}
</script>

<template>
    <NCard :title="$t('Update Password')">
        <div class="flex flex-col gap-2 sm:gap-4">
            <header>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $t('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </header>

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
                <NFormItem first :label="$t('Current Password')" path="current_password">
                    <NInput
                        ref="currentPasswordInput"
                        v-model:value="model.current_password"
                        type="password"
                        :placeholder="$t('Please enter :name', { name: $t('Current Password') })"
                        :input-props="{ autocomplete: 'password' }"
                    />
                </NFormItem>

                <NFormItem first :label="$t('New Password')" path="password">
                    <NInput
                        ref="passwordInput"
                        v-model:value="model.password"
                        type="password"
                        :placeholder="$t('Please enter :name', { name: $t('New Password') })"
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
                        <NButton type="primary" attr-type="submit" class="px-4">
                            {{ $t('Save') }}
                        </NButton>
                    </div>
                </NFormItem>
            </NForm>
        </div>
    </NCard>
</template>
