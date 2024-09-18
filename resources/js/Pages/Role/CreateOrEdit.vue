<script setup lang="ts">
import type { Role } from '@/types'
import type { FormInst, FormRules } from 'naive-ui'
import { useForm } from '@inertiajs/vue3'
import { useWindowSize } from '@vueuse/core'
import TablerPlus from '~icons/tabler/plus'
import { trans } from 'laravel-vue-i18n'
import { NButton, NForm, NFormItem, NIcon, NInput, NModal, useMessage } from 'naive-ui'
import { computed, ref, useTemplateRef } from 'vue'

const props = defineProps<{ role?: Role }>()

const message = useMessage()

const showModal = ref(false)

const model = useForm({
    name: props.role?.name || '',
    title: props.role?.title || '',
    description: props.role?.description || '',
})

const formRef = useTemplateRef<FormInst | null>('formRef')

const rules: FormRules = {
    name: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Role Name') }),
            trigger: ['input', 'change'],
        },
        {
            pattern: /^\w+(?: \w+)*$/,
            renderMessage: () => trans('validation.regex', { Attribute: trans('Role Name') }),
            trigger: ['input', 'change'],
        },
    ],
    title: [
        {
            type: 'string',
            required: true,
            renderMessage: () => trans('Please enter :name', { name: trans('Role Title') }),
            trigger: ['input', 'change'],
        },
        {
            pattern: /^(?!\s).*(?<!\s)$/,
            renderMessage: () => trans('validation.regex', { Attribute: trans('Role Title') }),
            trigger: ['input', 'change'],
        },
    ],
    description: [
        {
            type: 'string',
            renderMessage: () => trans('Please enter :name', { name: trans('Description') }),
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
                props.role ? 'put' : 'post',
                route(props.role ? 'role.update' : 'role.store', props.role?.id),
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
            <template v-if="!role" #icon>
                <NIcon :component="TablerPlus" />
            </template>

            {{ role?.id ? $t('Edit') : $t('Create') }}
        </NButton>

        <NModal
            v-model:show="showModal"
            preset="card"
            :title="role?.id ? $t('Edit') : $t('Create')"
            class="w-full max-w-xl"
        >
            <NForm ref="formRef" :model :rules :disabled="model.processing" :label-placement label-width="80">
                <NFormItem first :label="$t('Role Name')" path="name">
                    <NInput v-model:value="model.name" />
                </NFormItem>

                <NFormItem first :label="$t('Role Title')" path="title">
                    <NInput v-model:value="model.title" />
                </NFormItem>

                <NFormItem first :label="$t('Description')" path="description">
                    <NInput v-model:value="model.description" type="textarea" />
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
