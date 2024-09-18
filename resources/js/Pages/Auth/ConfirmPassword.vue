<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NButton, NForm, NFormItem, NInput } from 'naive-ui'
import { useTemplateRef } from 'vue'

defineOptions({ layout: GuestLayout })

const formRef = useTemplateRef<FormInst | null>('formRef')

const model = useForm({
    password: '',
})

const rules: FormRules = {
    password: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Password') }),
            trigger: ['input', 'change'],
        },
    ],
}

function submit(e: Event) {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            model.post(route('password.confirm'), {
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
        <Head :title="$t('Confirm Password')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t('This is a secure area of the application. Please confirm your password before continuing.')
                    }}
                </div>

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
                    <NFormItem first :label="$t('Password')" path="password">
                        <NInput
                            v-model:value="model.password"
                            type="password"
                            :placeholder="$t('Please enter :name', { name: $t('Password') })"
                            :input-props="{ autocomplete: 'current-password' }"
                        />
                    </NFormItem>

                    <NFormItem :show-label="false" :show-feedback="false" class="justify-items-end">
                        <div class="flex items-center gap-2">
                            <NButton type="primary" attr-type="submit" class="px-4">
                                {{ $t('Confirm') }}
                            </NButton>
                        </div>
                    </NFormItem>
                </NForm>
            </div>
        </div>
    </div>
</template>
