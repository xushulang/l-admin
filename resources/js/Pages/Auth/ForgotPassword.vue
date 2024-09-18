<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NAutoComplete, NButton, NForm, NFormItem } from 'naive-ui'
import { computed, useTemplateRef } from 'vue'

defineOptions({ layout: GuestLayout })

defineProps<{ status?: string }>()

const formRef = useTemplateRef<FormInst | null>('formRef')

const model = useForm({
    email: '',
})

const rules: FormRules = {
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
        const prefix = model.email.split('@')[0]
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
            model.post(route('password.email'), {
                onFinish: () => {
                    model.reset()
                },
            })
        }
    })
}
</script>

<template>
    <div>
        <Head :title="$t('Forgot Password')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t(
                            'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
                        )
                    }}
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ status }}
                </div>

                <NForm
                    ref="formRef"
                    :model
                    :rules
                    label-placement="left"
                    require-mark-placement="right-hanging"
                    :disabled="model.processing"
                    class="w-full"
                    @submit.prevent="submit"
                >
                    <NFormItem first :label="$t('Email')" path="email">
                        <NAutoComplete
                            v-model:value="model.email"
                            :placeholder="$t('Please enter :name', { name: $t('Email') })"
                            :options="autoCompleteOptions"
                            :input-props="{ autocomplete: 'email' }"
                        />
                    </NFormItem>

                    <NFormItem :show-label="false" :show-feedback="false" class="justify-items-end">
                        <div class="flex items-center gap-2">
                            <NButton type="primary" attr-type="submit" class="px-4">
                                {{ $t('Email Password Reset Link') }}
                            </NButton>
                        </div>
                    </NFormItem>
                </NForm>
            </div>
        </div>
    </div>
</template>
