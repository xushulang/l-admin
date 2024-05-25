<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { FormInst, FormRules, NButton, NCard, NForm, NFormItem, NInput, NModal } from 'naive-ui';
import { nextTick, ref } from 'vue';

const showModal = ref(false);

const formRef = ref<FormInst | null>(null);
const disabled = ref(false);

const passwordInput = ref<HTMLInputElement | null>(null);

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
        {
            min: 8,
            message: trans('validation.min.string', { Attribute: trans('Password'), min: '8' }),
            trigger: ['input', 'change'],
        },
    ],
};

const confirmUserDeletion = () => {
    showModal.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = (e: Event) => {
    e.preventDefault();
    disabled.value = true;

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.delete(route('profile.destroy'), {
                onSuccess: () => {
                    model.value.password = '';
                },
                onError: () => passwordInput.value?.focus(),
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
    <n-card :title="$t('Delete Account')">
        <div class="flex flex-col gap-2 sm:gap-4">
            <header>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t(
                            'Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'
                        )
                    }}
                </p>
            </header>

            <div>
                <n-button type="error" @click="confirmUserDeletion">{{ $t('Delete Account') }}</n-button>

                <n-modal v-model:show="showModal" preset="card" :title="$t('Confirm Password')" class="max-w-3xl">
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $t('Are you sure you want to delete your account?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{
                                $t(
                                    'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.'
                                )
                            }}
                        </p>

                        <div class="mt-6">
                            <n-form
                                :model="model"
                                :rules="rules"
                                ref="formRef"
                                label-placement="left"
                                require-mark-placement="right-hanging"
                                :disabled="disabled"
                                @submit.prevent="deleteUser"
                            >
                                <n-form-item first :label="$t('Password')" path="password">
                                    <n-input
                                        ref="passwordInput"
                                        type="password"
                                        :placeholder="$t('Please enter :name', { name: $t('Password') })"
                                        v-model:value="model.password"
                                        :input-props="{ autocomplete: 'password' }"
                                        @keyup.enter="deleteUser"
                                    />
                                </n-form-item>
                            </n-form>
                        </div>
                    </div>

                    <template #footer>
                        <div class="flex justify-end gap-2 sm:gap-4">
                            <n-button @click="showModal = false">{{ $t('Cancel') }}</n-button>

                            <n-button type="error" @click="deleteUser" :disabled>{{ $t('Delete Account') }}</n-button>
                        </div>
                    </template>
                </n-modal>
            </div>
        </div>
    </n-card>
</template>
