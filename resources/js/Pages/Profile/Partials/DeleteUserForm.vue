<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import { router, useForm } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NButton, NCard, NForm, NFormItem, NInput, NModal } from 'naive-ui'
import { nextTick, ref, useTemplateRef } from 'vue'

const showModal = ref(false)

const formRef = useTemplateRef<FormInst | null>('formRef')
const disabled = ref(false)

const passwordInput = useTemplateRef<HTMLInputElement | null>('passwordInput')

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
        {
            min: 8,
            message: trans('validation.min.string', { Attribute: trans('Password'), min: '8' }),
            trigger: ['input', 'change'],
        },
    ],
}

function confirmUserDeletion() {
    showModal.value = true

    nextTick(() => passwordInput.value?.focus())
}

function deleteUser(e: Event) {
    e.preventDefault()
    disabled.value = true

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.delete(route('profile.destroy'), {
                onSuccess: () => {
                    model.password = ''
                },
                onError: () => passwordInput.value?.focus(),
                onFinish: () => {
                    model.password = ''
                },
            })
        }

        disabled.value = false
    })
}
</script>

<template>
    <NCard :title="$t('Delete Account')">
        <div class="flex flex-col gap-2 sm:gap-4">
            <header>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $t('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
            </header>

            <div>
                <NButton type="error" @click="confirmUserDeletion">
                    {{ $t('Delete Account') }}
                </NButton>

                <NModal v-model:show="showModal" preset="card" :title="$t('Confirm Password')" class="max-w-3xl">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $t('Are you sure you want to delete your account?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ $t('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mt-6">
                            <NForm
                                ref="formRef"
                                :model
                                :rules
                                label-placement="left"
                                require-mark-placement="right-hanging"
                                :disabled
                                @submit.prevent="deleteUser"
                            >
                                <NFormItem first :label="$t('Password')" path="password">
                                    <NInput
                                        ref="passwordInput"
                                        v-model:value="model.password"
                                        type="password"
                                        :placeholder="$t('Please enter :name', { name: $t('Password') })"
                                        :input-props="{ autocomplete: 'password' }"
                                        @keyup.enter="deleteUser"
                                    />
                                </NFormItem>
                            </NForm>
                        </div>
                    </div>

                    <template #footer>
                        <div class="flex justify-end gap-2 sm:gap-4">
                            <NButton @click="showModal = false">
                                {{ $t('Cancel') }}
                            </NButton>

                            <NButton type="error" :disabled @click="deleteUser">
                                {{ $t('Delete Account') }}
                            </NButton>
                        </div>
                    </template>
                </NModal>
            </div>
        </div>
    </NCard>
</template>
