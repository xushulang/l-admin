<script setup lang="ts">
import type { FormInst, FormRules } from 'naive-ui'
import { useAdminStore } from '@/Stores/admin'
import { router, useForm } from '@inertiajs/vue3'
import { useUrlSearchParams, useWindowSize } from '@vueuse/core'
import { trans } from 'laravel-vue-i18n'
import { NButton, NCollapseTransition, NForm, NFormItem, NInput } from 'naive-ui'
import { computed, onBeforeMount, toRef, useTemplateRef } from 'vue'

const showRoleSearchForm = toRef(useAdminStore().value, 'showRoleSearchForm')

const model = useForm({
    search: '',
})

const formRef = useTemplateRef<FormInst | null>('formRef')

const rules: FormRules = {
    search: {
        type: 'string',
        required: true,
        renderMessage: () => trans('Please enter :name', { name: trans('Keywords') }),
        trigger: ['input', 'change', 'blur'],
    },
}

const { width } = useWindowSize()

const labelPlacement = computed(() => (width.value < 640 ? 'top' : 'left'))

function submit(e: Event) {
    e.preventDefault()

    formRef.value?.validate((errors) => {
        if (!errors) {
            router.reload({
                data: model.data(),
                onFinish: () => {},
            })
        }
    })
}

function reset() {
    model.reset()
}

onBeforeMount(() => {
    model.search = (useUrlSearchParams().search as string) || ''
})
</script>

<template>
    <NCollapseTransition :show="showRoleSearchForm">
        <NForm
            ref="formRef"
            :model
            :rules
            :label-placement
            inline
            class="flex justify-between items-center"
            @submit.prevent="submit"
        >
            <NFormItem first :label="$t('Role Title')" path="search">
                <NInput
                    v-model:value="model.search"
                    :placeholder="$t('Please enter :name', { name: $t('Keywords') })"
                />
            </NFormItem>

            <NFormItem>
                <div class="flex gap-2">
                    <NButton type="primary" attr-type="submit">
                        {{ $t('Search') }}
                    </NButton>
                    <NButton @click="reset">
                        {{ $t('Reset') }}
                    </NButton>
                </div>
            </NFormItem>
        </NForm>
    </NCollapseTransition>
</template>
