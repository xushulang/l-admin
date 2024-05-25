<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import {
    FormInst,
    FormRules,
    NAutoComplete,
    NButton,
    NCard,
    NCountdown,
    NForm,
    NFormItem,
    NInput,
    useMessage,
} from 'naive-ui';
import { computed, ref } from 'vue';

defineOptions({ layout: GuestLayout });

const message = useMessage();

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const model = ref({
    name: '',
    phone: '',
    email: '',
    password: '',
    password_confirmation: '',
    code: '',
    terms: false,
});

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
                return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(value);
            },
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
                return value === model.value.password;
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
};

const smsSent = ref(false);

const autoCompleteOptions = computed(() => {
    return ['@outlook.com', '@163.com', '@qq.com'].map((suffix) => {
        const prefix = model.value.email.split('@')[0];
        return {
            label: prefix + suffix,
            value: prefix + suffix,
        };
    });
});

const renderCountdown = ({ seconds }: { seconds: number }): string => {
    return String(seconds).padStart(2, '0') + '秒后可重新发送';
};

const getCode = (): void => {
    formRef.value?.validate(
        (errors) => {
            if (!errors) {
                window.axios.post(route('verification.code.phone'), { phone: model.value.phone }).then((response) => {
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

const submit = (e: Event): void => {
    e.preventDefault();
    disabled.value = true;

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.post(route('register'), model.value, {
                onFinish: () => {
                    model.value.password = '';
                    model.value.password_confirmation = '';
                },
            });
        }

        disabled.value = false;
    });
};
</script>

<template>
    <div>
        <Head :title="$t('Register')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center items-center gap-3">
                <ApplicationLogo full class="w-20 h-20 fill-current text-gray-500" />

                <n-card class="shadow-sm">
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
                        <n-form-item first :label="$t('Phone')" path="phone">
                            <n-input
                                :placeholder="$t('Please enter :name', { name: $t('Phone') })"
                                v-model:value="model.phone"
                                :input-props="{ autocomplete: 'tel' }"
                                autofocus
                            />
                        </n-form-item>

                        <n-form-item first :label="$t('SMS Verification Code')" path="code">
                            <div class="w-full flex justify-between gap-2">
                                <n-input
                                    type="text"
                                    :placeholder="$t('Please enter :name', { name: $t('Code') })"
                                    v-model:value="model.code"
                                />
                                <n-button v-if="!smsSent" type="info" @click="getCode">{{
                                    $t('Send Verification Code')
                                }}</n-button>
                                <n-button v-else type="error">
                                    <n-countdown
                                        :render="renderCountdown"
                                        :duration="59000"
                                        @finish="smsSent = false"
                                    />
                                </n-button>
                            </div>
                        </n-form-item>

                        <n-form-item first :label="$t('Name')" path="name">
                            <n-input
                                :placeholder="$t('Please enter :name', { name: $t('Name') })"
                                v-model:value="model.name"
                                :input-props="{ autocomplete: 'name' }"
                            />
                        </n-form-item>

                        <n-form-item first :label="$t('Email')" path="email">
                            <n-auto-complete
                                :placeholder="$t('Please enter :name', { name: $t('Email') })"
                                v-model:value="model.email"
                                :options="autoCompleteOptions"
                                :input-props="{ autocomplete: 'email' }"
                            />
                        </n-form-item>

                        <n-form-item first :label="$t('Password')" path="password">
                            <n-input
                                type="password"
                                :placeholder="$t('Please enter :name', { name: $t('Password') })"
                                v-model:value="model.password"
                                :input-props="{ autocomplete: 'new-password' }"
                            />
                        </n-form-item>

                        <n-form-item first :label="$t('Confirm Password')" path="password_confirmation">
                            <n-input
                                :placeholder="$t('Please enter :name', { name: $t('Confirm Password') })"
                                :disabled="!model.password"
                                v-model:value="model.password_confirmation"
                                type="password"
                                :input-props="{ autocomplete: 'new-password' }"
                            />
                        </n-form-item>

                        <n-form-item :show-label="false" :show-feedback="false" class="justify-items-end">
                            <div class="flex items-center gap-2">
                                <Link :href="route('login')" class="hover:text-blue-500">
                                    {{ $t('Already registered?') }}
                                </Link>

                                <n-button type="primary" attr-type="submit" class="px-4">{{ $t('Register') }}</n-button>
                            </div>
                        </n-form-item>
                    </n-form>
                </n-card>
            </div>
        </div>
    </div>
</template>
