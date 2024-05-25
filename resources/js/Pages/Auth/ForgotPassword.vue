<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { FormInst, FormRules, NAutoComplete, NButton, NForm, NFormItem } from 'naive-ui';
import { trans } from 'laravel-vue-i18n';

defineOptions({ layout: GuestLayout });

defineProps<{
    status?: string;
}>();

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const model = ref({
    email: '',
});

const rules: FormRules = {
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
            router.post(route('password.email'), model.value, {
                onFinish: () => {
                    model.value.email = '';
                },
            });
        }

        disabled.value = false;
    });
};
</script>

<template>
    <div>
        <Head :title="$t('Forgot Password')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t(
                            'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.'
                        )
                    }}
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ status }}
                </div>

                <n-form
                    :model="model"
                    :rules="rules"
                    ref="formRef"
                    label-placement="left"
                    require-mark-placement="right-hanging"
                    :disabled="disabled"
                    class="w-full"
                    @submit.prevent="submit"
                >
                    <n-form-item first :label="$t('Email')" path="email">
                        <n-auto-complete
                            :placeholder="$t('Please enter :name', { name: $t('Email') })"
                            v-model:value="model.email"
                            :options="autoCompleteOptions"
                            :input-props="{ autocomplete: 'email' }"
                        />
                    </n-form-item>

                    <n-form-item :show-label="false" :show-feedback="false" class="justify-items-end">
                        <div class="flex items-center gap-2">
                            <n-button type="primary" attr-type="submit" class="px-4">
                                {{ $t('Email Password Reset Link') }}
                            </n-button>
                        </div>
                    </n-form-item>
                </n-form>
            </div>
        </div>
    </div>
</template>
