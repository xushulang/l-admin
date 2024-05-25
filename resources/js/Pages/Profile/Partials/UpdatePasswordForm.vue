<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { FormInst, FormRules, NButton, NCard, NForm, NFormItem, NInput } from 'naive-ui';
import { ref } from 'vue';

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const model = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
});

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
                return value === model.value.password;
            },
            renderMessage: () => trans('validation.confirmed', { Attribute: trans('Password') }),
            trigger: ['input', 'change'],
        },
    ],
};

const submit = (e: Event) => {
    e.preventDefault();
    disabled.value = true;

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.put(route('password.update'), model.value, {
                onSuccess: () => {
                    model.value.current_password = '';
                    model.value.password = '';
                    model.value.password_confirmation = '';
                },
                onError: (errors) => {
                    if (errors.password) {
                        model.value.password = '';
                        model.value.password_confirmation = '';

                        passwordInput.value?.focus();
                    }

                    if (errors.current_password) {
                        model.value.current_password = '';

                        currentPasswordInput.value?.focus();
                    }
                },
            });
        }

        disabled.value = false;
    });
};
</script>

<template>
    <n-card :title="$t('Update Password')">
        <div class="flex flex-col gap-2 sm:gap-4">
            <header>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $t('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </header>

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
                <n-form-item first :label="$t('Current Password')" path="current_password">
                    <n-input
                        ref="currentPasswordInput"
                        type="password"
                        :placeholder="$t('Please enter :name', { name: $t('Current Password') })"
                        v-model:value="model.current_password"
                        :input-props="{ autocomplete: 'password' }"
                    />
                </n-form-item>

                <n-form-item first :label="$t('New Password')" path="password">
                    <n-input
                        ref="passwordInput"
                        type="password"
                        :placeholder="$t('Please enter :name', { name: $t('New Password') })"
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
                        <n-button type="primary" attr-type="submit" class="px-4">{{ $t('Save') }}</n-button>
                    </div>
                </n-form-item>
            </n-form>
        </div>
    </n-card>
</template>
