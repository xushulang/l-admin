<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { FormInst, FormRules, NAutoComplete, NButton, NCard, NForm, NFormItem, NInput, useMessage } from 'naive-ui';
import { computed, ref } from 'vue';

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const message = useMessage();

const user = usePage().props.auth.user;

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const model = ref({
    name: user.name,
    username: user.username,
    phone: user.phone,
    email: user.email,
});

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
                return /^[0-9a-z_-]{2,20}$/.test(value);
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
                return /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/.test(value);
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
            router.patch(route('profile.update'), model.value, {
                onSuccess: () => {
                    message.success(trans('Update :name', { name: trans('Success') }));
                },
            });
        }

        disabled.value = false;
    });
};
</script>

<template>
    <n-card :title="$t('Profile Information')">
        <div class="flex flex-col gap-2 sm:gap-4">
            <header>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $t("Update your account's profile information and email address.") }}
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
                <n-form-item first :label="$t('Name')" path="name">
                    <n-input
                        :placeholder="$t('Please enter :name', { name: $t('Name') })"
                        v-model:value="model.name"
                        :input-props="{ autocomplete: 'name' }"
                    />
                </n-form-item>

                <n-form-item first :label="$t('Username')" path="username">
                    <n-input
                        :placeholder="$t('Please enter :name', { name: $t('Username') })"
                        v-model:value="model.username"
                        :input-props="{ autocomplete: 'username' }"
                    />
                </n-form-item>

                <n-form-item first :label="$t('Phone')" path="phone">
                    <n-input
                        :placeholder="$t('Please enter :name', { name: $t('Phone') })"
                        v-model:value="model.phone"
                        :input-props="{ autocomplete: 'tel' }"
                    />
                </n-form-item>

                <n-form-item first :label="$t('Email')" path="email">
                    <n-auto-complete
                        :placeholder="$t('Please enter :name', { name: $t('Email') })"
                        v-model:value="model.email"
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
