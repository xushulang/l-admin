<script setup lang="ts">
import type { Data, User } from '@/types'
import type { DataTableColumns, PaginationInfo, PaginationProps } from 'naive-ui'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { handleSearchParams } from '@/Utils/helper'
import { router } from '@inertiajs/vue3'
import { useWindowSize } from '@vueuse/core'
import { trans } from 'laravel-vue-i18n'
import { NAvatar, NButton, NCard, NDataTable, NSpace, NTime, useDialog, useMessage } from 'naive-ui'
import { computed, h, reactive, ref } from 'vue'
import CreateOrEdit from './CreateOrEdit.vue'
import Toolbar from './Toolbar.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps<{ users: Data<User> }>()

const dialog = useDialog()
const message = useMessage()

const { width } = useWindowSize()

const loading = ref(false)

const columns: DataTableColumns<User> = [
    {
        title: 'ID',
        key: 'id',
        fixed: 'left',
    },
    {
        title: () => trans('Name'),
        key: 'name',
    },
    {
        title: () => trans('Username'),
        key: 'username',
    },
    {
        title: () => trans('Phone'),
        key: 'phone',
    },
    {
        title: () => trans('Email'),
        key: 'email',
    },
    {
        title: () => trans('Profile Photo'),
        key: 'profile_photo_url',
        render(row) {
            return h(NAvatar, { round: true, size: 'small', src: row.profile_photo_url })
        },
    },
    {
        title: () => trans(':name Time', { name: trans('Create') }),
        key: 'created_at',
        render(row) {
            return h(NTime, { time: new Date(row.created_at) })
        },
    },
    {
        title: () => trans(':name Time', { name: trans('Update') }),
        key: 'updated_at',
        render(row) {
            return h(NTime, { time: new Date(row.updated_at) })
        },
    },
    {
        title: () => trans('Action'),
        key: 'action',
        fixed: computed(() => (width.value > 640 ? 'right' : undefined)) as unknown as 'left' | 'right' | undefined,
        render(row) {
            return h(NSpace, { justify: 'center', size: 'small' }, () => [
                h(CreateOrEdit, { user: row }),
                h(NButton, { size: 'small', onClick: () => destroy(row.id) }, { default: () => trans('Delete') }),
            ])
        },
    },
]

const pagination: PaginationProps = reactive({
    page: props.users.current_page,
    pageSize: props.users.per_page,
    showSizePicker: true,
    pageSizes: [15, 30, 45, 60, 100],
    itemCount: props.users.total,
    showQuickJumper: true,
    prefix: (info: PaginationInfo) => h('span', trans(':count in Total', { count: info.itemCount?.toString() || '0' })),
    simple: computed(() => width.value < 640),
    onChange: (page: number) => {
        getData(page, props.users.per_page)
    },
    onUpdatePageSize: (pageSize: number) => {
        getData(1, pageSize)
    },
})

function getData(page: number, pageSize: number) {
    loading.value = true

    router.reload({
        data: handleSearchParams({ page, per_page: pageSize }),
        onSuccess: () => {
            loading.value = false
        },
    })
}

function destroy(id: number) {
    dialog.warning({
        title: trans('Prompt'),
        content: trans('Are you sure you want to delete :name?', { name: trans('User') }),
        positiveText: trans('Confirm'),
        negativeText: trans('Cancel'),
        onPositiveClick: () => {
            router.delete(route('user.destroy', id), {
                onSuccess: (res) => {
                    message.create(res.props.flash!.message, {
                        type: res.props.flash!.status,
                    })
                },
            })
        },
    })
}
</script>

<template>
    <NCard :bordered="false" size="small">
        <template #header>
            <Toolbar />
        </template>

        <NDataTable
            :loading
            :columns
            :data="users.data"
            :row-key="(row: User) => row.id"
            :pagination
            :scroll-x="1200"
        />
    </NCard>
</template>
