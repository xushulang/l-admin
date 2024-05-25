<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { FormInst, FormRules, NButton, NForm, NFormItem, NInput } from 'naive-ui';
import { ref } from 'vue';

defineOptions({ layout: GuestLayout });

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const model = ref({
    password: '',
});

const rules: FormRules = {
    password: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Password') }),
            trigger: ['input', 'change'],
        },
    ],
};

const submit = (e: Event) => {
    e.preventDefault();
    disabled.value = true;

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.post(route('password.confirm'), model.value, {
                onFinish: () => {
                    model.value.password = '';
                },
            });
        }

        disabled.value = false;
    });
};
</script>

<template>
    <div>
        <Head :title="$t('Confirm Password')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t('This is a secure area of the application. Please confirm your password before continuing.')
                    }}
                </div>

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
                    <n-form-item first :label="$t('Password')" path="password">
                        <n-input
                            type="password"
                            :placeholder="$t('Please enter :name', { name: $t('Password') })"
                            v-model:value="model.password"
                            :input-props="{ autocomplete: 'current-password' }"
                        />
                    </n-form-item>

                    <n-form-item :show-label="false" :show-feedback="false" class="justify-items-end">
                        <div class="flex items-center gap-2">
                            <n-button type="primary" attr-type="submit" class="px-4">
                                {{ $t('Confirm') }}
                            </n-button>
                        </div>
                    </n-form-item>
                </n-form>
            </div>
        </div>
    </div>
</template>
