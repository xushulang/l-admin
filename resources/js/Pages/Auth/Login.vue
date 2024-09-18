<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NButton, NCard, NCheckbox, NCountdown, NForm, NFormItem, NH2, NImage, NInput, useMessage } from 'naive-ui'
import { onMounted, ref, useTemplateRef } from 'vue'

defineOptions({ layout: GuestLayout })

defineProps<{
    canResetPassword?: boolean
    status?: string
}>()

const message = useMessage()

const loginType = ref('account')
const appName = ref<string>(import.meta.env.VITE_APP_NAME)

const formRef = useTemplateRef<FormInst | null>('formRef')
const captchaDisable = ref<boolean>(import.meta.env.VITE_CAPTCHA_DISABLE === 'true')
const captcha = ref({
    sensitive: false,
    key: '',
    img: '',
})

const model = useForm({
    auth: '',
    password: '',
    code: '',
    key: '',
    captcha: '',
    remember: false,
})

const rules: FormRules = {
    auth: [
        {
            type: 'string',
            required: true,
            renderMessage: () =>
                trans('Please enter :name', { name: `${trans('Phone')}/${trans('Email')}/${trans('Username')}` }),
            trigger: ['input', 'change'],
        },
        {
            key: 'phone',
            validator: (rule, value) => {
                if (loginType.value === 'code') {
                    return /^\+?\(?\d{3}\)?[-\s.]?\d{3}[-\s.]?\d{4,6}$/.test(value)
                }
            },
            renderMessage: () => trans('validation.regex', { Attribute: trans('Phone') }),
            trigger: ['input', 'change'],
        },
    ],
    password: [
        {
            type: 'string',
            validator: (rule, value) => {
                if (!value && loginType.value === 'account') {
                    return false
                }
            },
            renderMessage: () => trans('Please enter :name', { name: trans('Password') }),
            trigger: ['input', 'change'],
        },
    ],
    code: [
        {
            type: 'string',
            validator: (rule, value) => {
                if (!value && loginType.value === 'code') {
                    return false
                }
            },
            renderMessage: () => trans('Please enter :name', { name: trans('Code') }),
            trigger: ['input', 'change'],
        },
    ],
    key: [
        {
            type: 'string',
            required: !captchaDisable.value,
            renderMessage: () => trans('Please enter :name', { name: trans('Code') }),
            trigger: ['input', 'change'],
        },
    ],
    captcha: [
        {
            type: 'string',
            required: !captchaDisable.value,
            renderMessage: () => trans('Please enter :name', { name: trans('Captcha') }),
            trigger: ['input', 'change'],
        },
    ],
    remember: [
        {
            type: 'boolean',
            required: true,
            renderMessage: () => trans('Remember me'),
            trigger: ['input', 'change'],
        },
    ],
}

const smsSent = ref(false)

function renderCountdown({ seconds }: { seconds: number }): string {
    return `${String(seconds).padStart(2, '0')}秒后可重新发送`
}

function getCode(): void {
    formRef.value?.validate(
        (errors) => {
            if (!errors) {
                axios.post(route('verification.code.phone'), { phone: model.auth }).then((response) => {
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

function getCaptcha(): void {
    axios.get(`/captcha/api/${import.meta.env.VITE_CAPTCHA_TYPE}?${Math.random()}`).then((response) => {
        captcha.value.sensitive = response.data.sensitive
        captcha.value.key = response.data.key
        captcha.value.img = response.data.img
        model.key = response.data.key
    })
}

function submit(e: Event): void {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            model.post(route('login'), {
                onError: (errors) => {
                    message.error(Object.values(errors)[0])
                },
            })
        }
    })
}

onMounted(() => {
    if (!captchaDisable.value) {
        getCaptcha()
    }
})
</script>

<template>
    <div>
        <Head :title="$t('Log in')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center items-center gap-3">
                <ApplicationLogo full class="w-20 h-20 fill-current text-gray-500" />

                <NCard class="shadow-sm" :segmented="{ content: true, footer: 'soft' }">
                    <template #header>
                        <NH2 class="!m-0">
                            {{ $t('Welcome to :name!', { name: appName }) }}
                        </NH2>
                    </template>

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
                        <NFormItem first :label="`${$t('Phone')}/${$t('Email')}/${$t('Username')}`" path="auth">
                            <NInput
                                v-model:value="model.auth"
                                type="text"
                                :placeholder=" `${$t('Please enter :name', { name: $t('Phone') })}/${$t('Email')}/${$t('Username')}` "
                                autofocus
                            />
                        </NFormItem>

                        <NFormItem
                            v-if="loginType === 'account'"
                            first
                            show-require-mark
                            :label="$t('Password')"
                            path="password"
                        >
                            <NInput
                                v-model:value="model.password"
                                type="password"
                                :placeholder="$t('Please enter :name', { name: $t('Password') })"
                            />
                        </NFormItem>

                        <NFormItem v-else first show-require-mark :label="$t('SMS Verification Code')" path="code">
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
                                    <NCountdown
                                        :render="renderCountdown"
                                        :duration="59000"
                                        @finish="smsSent = false"
                                    />
                                </NButton>
                            </div>
                        </NFormItem>

                        <NFormItem v-if="!captchaDisable" first :label="$t('Captcha')" path="captcha">
                            <div class="w-full flex justify-between gap-2">
                                <NInput
                                    v-model:value="model.captcha"
                                    type="text"
                                    :placeholder="$t('Please enter :name', { name: $t('Captcha') })"
                                />
                                <NImage
                                    :src="captcha.img"
                                    preview-disabled
                                    class="cursor-pointer"
                                    @click="getCaptcha"
                                />
                            </div>
                        </NFormItem>

                        <NFormItem first :show-label="false" :show-feedback="false" path="remember">
                            <NCheckbox v-model:checked="model.remember">
                                {{ $t('Remember me') }}
                            </NCheckbox>
                        </NFormItem>

                        <NFormItem :show-label="false" :show-feedback="false" class="justify-items-end">
                            <div class="flex items-center gap-2">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="hover:text-[var(--n-color-target)]"
                                >
                                    {{ $t('Forgot your password?') }}
                                </Link>

                                <NButton type="primary" attr-type="submit" class="px-4">
                                    {{ $t('Log In') }}
                                </NButton>
                            </div>
                        </NFormItem>
                    </NForm>

                    <template #footer>
                        <div class="flex justify-between">
                            <Link
                                v-if="route().has('register')"
                                :href="route('register')"
                                class="hover:text-[var(--n-color-target)]"
                            >
                                {{ $t('Register Now') }}
                            </Link>

                            <div class="ml-auto">
                                <div
                                    v-if="loginType === 'account'"
                                    class="hover:text-[var(--n-color-target)] cursor-pointer"
                                    @click="loginType = 'code'"
                                >
                                    {{ $t('SMS Verification Code Login') }}
                                </div>
                                <div
                                    v-else
                                    class="hover:text-[var(--n-color-target)] cursor-pointer"
                                    @click="loginType = 'account'"
                                >
                                    {{ $t('Account Password Login') }}
                                </div>
                            </div>
                        </div>
                    </template>
                </NCard>
            </div>
        </div>
    </div>
</template>
