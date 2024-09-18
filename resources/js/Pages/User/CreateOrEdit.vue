<script setup lang="ts">
import type { User } from '@/types'
import type { FormInst, FormRules } from 'naive-ui'
import { useForm } from '@inertiajs/vue3'
import { useWindowSize } from '@vueuse/core'
import TablerPlus from '~icons/tabler/plus'
import { trans } from 'laravel-vue-i18n'
import { NButton, NForm, NFormItem, NIcon, NInput, NModal, useMessage } from 'naive-ui'
import { computed, ref, useTemplateRef } from 'vue'

const props = defineProps<{ user?: User }>()

const message = useMessage()

const showModal = ref(false)

const model = useForm({
    name: props.user?.name || '',
    username: props.user?.username || '',
    phone: props.user?.phone || '',
    email: props.user?.email || '',
    password: '',
})

const formRef = useTemplateRef<FormInst | null>('formRef')

const rules: FormRules = {
    name: [
        {
            type: 'string',
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
    ],
    phone: [
        {
            type: 'string',
            renderMessage: () => trans('Please enter :name', { name: trans('Phone') }),
            trigger: ['input', 'change'],
        },
        {
            validator: (rule, value) => {
                return /^\+?\(?\d{3}\)?[-\s.]?\d{3}[-\s.]?\d{4,6}$/.test(value)
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
            renderMessage: () => trans('Please enter :name', { name: trans('Password') }),
            trigger: ['input', 'change'],
        },
        {
            min: 8,
            renderMessage: () => trans('validation.min.string', { Attribute: trans('Password'), min: '8' }),
            trigger: ['input', 'change'],
        },
    ],
}

const { width } = useWindowSize()

const labelPlacement = computed(() => (width.value < 640 ? 'top' : 'left'))

function submit(e: Event) {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            model.submit(
                props.user ? 'put' : 'post',
                route(props.user ? 'user.update' : 'user.store', props.user?.id),
                {
                    onSuccess: (res) => {
                        showModal.value = false

                        message.create(res.props.flash!.message, {
                            type: res.props.flash!.status,
                        })
                    },
                    onError: (errors) => {
                        message.error(Object.values(errors).join(', '))
                    },
                },
            )
        }
    })
}
</script>

<template>
    <div>
        <NButton type="primary" size="small" @click="showModal = true">
            <template v-if="!user" #icon>
                <NIcon :component="TablerPlus" />
            </template>

            {{ user?.id ? $t('Edit') : $t('Create') }}
        </NButton>

        <NModal
            v-model:show="showModal"
            preset="card"
            :title="user?.id ? $t('Edit') : $t('Create')"
            class="w-full max-w-xl"
        >
            <NForm ref="formRef" :model :rules :disabled="model.processing" :label-placement label-width="80">
                <NFormItem first :label="$t('Name')" path="name">
                    <NInput v-model:value="model.name" />
                </NFormItem>

                <NFormItem first :label="$t('Username')" path="username">
                    <NInput v-model:value="model.username" />
                </NFormItem>

                <NFormItem first :label="$t('Phone')" path="phone">
                    <NInput v-model:value="model.phone" />
                </NFormItem>

                <NFormItem first :label="$t('Email')" path="email">
                    <NInput v-model:value="model.email" />
                </NFormItem>

                <NFormItem first :label="$t('Password')" path="password">
                    <NInput v-model:value="model.password" show-password-on="mousedown" type="password" />
                </NFormItem>

                <NFormItem>
                    <div class="w-full flex justify-end items-center gap-4">
                        <NButton type="primary" :disabled="model.processing" @click="submit">
                            {{ $t('Confirm') }}
                        </NButton>
                        <NButton @click="showModal = false">
                            {{ $t('Cancel') }}
                        </NButton>
                    </div>
                </NFormItem>
            </NForm>
        </NModal>
    </div>
</template>
