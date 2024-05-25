<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import { trans } from 'laravel-vue-i18n';
import {
    FormInst,
    FormRules,
    NButton,
    NCard,
    NCheckbox,
    NCountdown,
    NForm,
    NFormItem,
    NH2,
    NImage,
    NInput,
    useMessage,
} from 'naive-ui';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineOptions({ layout: GuestLayout });

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const message = useMessage();

const loginType = ref('account');
const appName = ref<string>(import.meta.env.VITE_APP_NAME);

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);
const captchaDisable = ref<boolean>(import.meta.env.VITE_CAPTCHA_DISABLE);
const captcha = ref({
    sensitive: false,
    key: '',
    img: '',
});

const model = ref({
    auth: '',
    password: '',
    code: '',
    key: '',
    captcha: '',
    remember: false,
});

const rules: FormRules = {
    auth: [
        {
            type: 'string',
            required: true,
            renderMessage: () =>
                trans('Please enter :name', { name: trans('Phone') + '/' + trans('Email') + '/' + trans('Username') }),
            trigger: ['input', 'change'],
        },
        {
            key: 'phone',
            validator: (rule, value) => {
                if (loginType.value === 'code') {
                    return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(value);
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
                    return false;
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
                    return false;
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
};

const smsSent = ref(false);

const renderCountdown = ({ seconds }: { seconds: number }): string => {
    return String(seconds).padStart(2, '0') + '秒后可重新发送';
};

const getCode = (): void => {
    formRef.value?.validate(
        (errors) => {
            if (!errors) {
                window.axios.post(route('api.verification.code.send'), { phone: model.value.auth }).then((response) => {
                    if (response.data.status === 'success') {
                        message.success(response.data.message);
                        smsSent.value = true;
                    } else {
                        message.error(response.data.message);
                    }
                });
            }
        },
        (rule) => {
            return rule?.key === 'phone';
        }
    );
};

const getCaptcha = (): void => {
    window.axios.get('/captcha/api/' + import.meta.env.VITE_CAPTCHA_TYPE + '?' + Math.random()).then((response) => {
        captcha.value.sensitive = response.data.sensitive;
        captcha.value.key = response.data.key;
        captcha.value.img = response.data.img;
        model.value.key = response.data.key;
    });
};

const submit = (e: Event): void => {
    e.preventDefault();
    disabled.value = true;

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.post(route('login'), model.value, {
                onError: (errors) => {
                    message.error(Object.values(errors)[0]);
                },
            });
        }

        disabled.value = false;
    });
};

onMounted(() => {
    getCaptcha();
});
</script>

<template>
    <div>
        <Head :title="$t('Log in')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center items-center gap-3">
                <ApplicationLogo full class="w-20 h-20 fill-current text-gray-500" />

                <n-card class="shadow-sm" :segmented="{ content: true, footer: 'soft' }">
                    <template #header>
                        <n-h2 class="!m-0">{{ $t('Welcome to :name!', { name: appName }) }}</n-h2>
                    </template>

                    <n-form
                        :model="model"
                        :rules="rules"
                        ref="formRef"
                        label-placement="top"
                        require-mark-placement="right-hanging"
                        :label-width="160"
                        :disabled="disabled"
                        @submit.prevent="submit"
                    >
                        <n-form-item first :label="$t('Phone') + '/' + $t('Email') + '/' + $t('Username')" path="auth">
                            <n-input
                                type="text"
                                :placeholder="
                                    $t('Please enter :name', { name: $t('Phone') }) +
                                    '/' +
                                    $t('Email') +
                                    '/' +
                                    $t('Username')
                                "
                                v-model:value="model.auth"
                                autofocus
                            />
                        </n-form-item>

                        <n-form-item
                            v-if="loginType === 'account'"
                            first
                            show-require-mark
                            :label="$t('Password')"
                            path="password"
                        >
                            <n-input
                                type="password"
                                :placeholder="$t('Please enter :name', { name: $t('Password') })"
                                v-model:value="model.password"
                            />
                        </n-form-item>

                        <n-form-item v-else first show-require-mark :label="$t('SMS Verification Code')" path="code">
                            <div class="w-full flex justify-between gap-2">
                                <n-input
                                    type="text"
                                    :placeholder="$t('Please enter :name', { name: $t('Code') })"
                                    v-model:value="model.code"
                                />
                                <n-button v-if="!smsSent" type="info" @click="getCode">
                                    {{ $t('Send Verification Code') }}
                                </n-button>
                                <n-button v-else type="error">
                                    <n-countdown
                                        :render="renderCountdown"
                                        :duration="59000"
                                        @finish="smsSent = false"
                                    />
                                </n-button>
                            </div>
                        </n-form-item>

                        <n-form-item v-if="!captchaDisable" first :label="$t('Captcha')" path="captcha">
                            <div class="w-full flex justify-between gap-2">
                                <n-input
                                    type="text"
                                    :placeholder="$t('Please enter :name', { name: $t('Captcha') })"
                                    v-model:value="model.captcha"
                                />
                                <n-image
                                    :src="captcha.img"
                                    preview-disabled
                                    @click="getCaptcha"
                                    class="cursor-pointer"
                                />
                            </div>
                        </n-form-item>

                        <n-form-item first :show-label="false" :show-feedback="false" path="remember">
                            <n-checkbox v-model:checked="model.remember">{{ $t('Remember me') }}</n-checkbox>
                        </n-form-item>

                        <n-form-item :show-label="false" :show-feedback="false" class="justify-items-end">
                            <div class="flex items-center gap-2">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="hover:text-[var(--n-color-target)]"
                                >
                                    {{ $t('Forgot your password?') }}
                                </Link>

                                <n-button type="primary" attr-type="submit" class="px-4">{{ $t('Log In') }}</n-button>
                            </div>
                        </n-form-item>
                    </n-form>

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
                                    @click="loginType = 'code'"
                                    class="hover:text-[var(--n-color-target)] cursor-pointer"
                                >
                                    {{ $t('SMS Verification Code Login') }}
                                </div>
                                <div
                                    v-else
                                    @click="loginType = 'account'"
                                    class="hover:text-[var(--n-color-target)] cursor-pointer"
                                >
                                    {{ $t('Account Password Login') }}
                                </div>
                            </div>
                        </div>
                    </template>
                </n-card>
            </div>
        </div>
    </div>
</template>
