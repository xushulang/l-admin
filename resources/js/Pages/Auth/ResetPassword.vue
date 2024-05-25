<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { FormInst, FormRules, NAutoComplete, NButton, NForm, NFormItem, NInput } from 'naive-ui';
import { computed, ref } from 'vue';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    email: string;
    token: string;
}>();

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const model = ref({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
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
            renderMessage: () => trans('Code'),
            trigger: ['input', 'change'],
        },
    ],
};

const autoCompleteOptions = computed(() => {
    return ['@outlook.com', '@163.com', '@qq.com'].map((suffix) => {
        const prefix = model.value.email.split('@')[0];
        return {
            label: prefix + suffix,
            value: prefix + suffix,
        };
    });
});

const submit = (e: Event) => {
    e.preventDefault();
    disabled.value = true;

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.post(route('password.store'), model.value, {
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
        <Head :title="$t('Reset Password')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
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
                    <n-form-item first :label="$t('Email')" path="email">
                        <n-auto-complete
                            :placeholder="$t('Email')"
                            v-model:value="model.email"
                            :options="autoCompleteOptions"
                            :input-props="{ autocomplete: 'email' }"
                        />
                    </n-form-item>

                    <n-form-item first :label="$t('Password')" path="password">
                        <n-input
                            type="password"
                            :placeholder="$t('Password')"
                            v-model:value="model.password"
                            :input-props="{ autocomplete: 'new-password' }"
                        />
                    </n-form-item>

                    <n-form-item first :label="$t('Confirm Password')" path="password_confirmation">
                        <n-input
                            :placeholder="$t('Confirm Password')"
                            :disabled="!model.password"
                            v-model:value="model.password_confirmation"
                            type="password"
                            :input-props="{ autocomplete: 'new-password' }"
                        />
                    </n-form-item>

                    <n-form-item :show-label="false" :show-feedback="false" class="justify-items-end">
                        <div class="flex items-center gap-2">
                            <n-button type="primary" attr-type="submit" class="px-4">
                                {{ $t('Reset Password') }}
                            </n-button>
                        </div>
                    </n-form-item>
                </n-form>
            </div>
        </div>
    </div>
</template>
