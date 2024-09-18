<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NAutoComplete, NButton, NCard, NForm, NFormItem, NInput, useMessage } from 'naive-ui'
import { computed, useTemplateRef } from 'vue'

defineProps<{
    mustVerifyEmail?: boolean
    status?: string
}>()

const message = useMessage()

const user = usePage().props.auth.user

const formRef = useTemplateRef<FormInst | null>('formRef')

const model = useForm({
    name: user.name,
    username: user.username,
    phone: user.phone,
    email: user.email,
})

const rules: FormRules = {
    name: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Name') }),
            trigger: ['input', 'change'],
        },
    ],
    username: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Username') }),
            trigger: ['input', 'change'],
        },
        {
            validator: (rule, value) => {
                return /^[0-9a-z_-]{2,20}$/.test(value)
            },
            renderMessage: () => trans('validation.regex', { Attribute: trans('Username') }),
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
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Email') }),
            trigger: ['input', 'change'],
        },
    ],
}

const autoCompleteOptions = computed(() => {
    return ['@outlook.com', '@163.com', '@qq.com'].map((suffix) => {
        const prefix = model.email?.split('@')[0]
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
            model.patch(route('profile.update'), {
                onSuccess: () => {
                    message.success(trans('Update :name', { name: trans('Success') }))
                },
            })
        }
    })
}
</script>

<template>
    <NCard :title="$t('Profile Information')">
        <div class="flex flex-col gap-2 sm:gap-4">
            <header>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $t("Update your account's profile information and email address.") }}
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
                <NFormItem first :label="$t('Name')" path="name">
                    <NInput
                        v-model:value="model.name"
                        :placeholder="$t('Please enter :name', { name: $t('Name') })"
                        :input-props="{ autocomplete: 'name' }"
                    />
                </NFormItem>

                <NFormItem first :label="$t('Username')" path="username">
                    <NInput
                        v-model:value="model.username"
                        :placeholder="$t('Please enter :name', { name: $t('Username') })"
                        :input-props="{ autocomplete: 'username' }"
                    />
                </NFormItem>

                <NFormItem first :label="$t('Phone')" path="phone">
                    <NInput
                        v-model:value="model.phone"
                        :placeholder="$t('Please enter :name', { name: $t('Phone') })"
                        :input-props="{ autocomplete: 'tel' }"
                    />
                </NFormItem>

                <NFormItem first :label="$t('Email')" path="email">
                    <NAutoComplete
                        v-model:value="model.email"
                        :placeholder="$t('Please enter :name', { name: $t('Email') })"
                        :options="autoCompleteOptions"
                        :input-props="{ autocomplete: 'email' }"
                    />

                    <div v-if="mustVerifyEmail && user.email_verified_at === null">
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ $t('Your email address is unverified.') }}
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            >
                                {{ $t('Click here to re-send the verification email.') }}
                            </Link>
                        </p>

                        <div
                            v-show="status === 'verification-link-sent'"
                            class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                        >
                            {{ $t('A new verification link has been sent to your email address.') }}
                        </div>
                    </div>
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
