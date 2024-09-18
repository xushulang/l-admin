<script setup lang="ts">
import type { Data, Role } from '@/types'
import type { DataTableColumns, PaginationInfo, PaginationProps } from 'naive-ui'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { handleSearchParams } from '@/Utils/helper'
import { router } from '@inertiajs/vue3'
import { useWindowSize } from '@vueuse/core'
import { trans } from 'laravel-vue-i18n'
import { NButton, NCard, NDataTable, NSpace, NTime, useDialog, useMessage } from 'naive-ui'
import { computed, h, reactive, ref } from 'vue'
import CreateOrEdit from './CreateOrEdit.vue'
import Toolbar from './Toolbar.vue'

defineOptions({ layout: AdminLayout })

const props = defineProps<{ roles: Data<Role> }>()

const dialog = useDialog()
const message = useMessage()

const { width } = useWindowSize()

const loading = ref(false)

const columns: DataTableColumns<Role> = [
    {
        title: 'ID',
        key: 'id',
        fixed: 'left',
    },
    {
        title: () => trans('Role Title'),
        key: 'title',
    },
    {
        title: () => trans('Role Name'),
        key: 'name',
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
                h(CreateOrEdit, { role: row }),
                h(NButton, { size: 'small', onClick: () => destroy(row.id) }, { default: () => trans('Delete') }),
            ])
        },
    },
]

const pagination: PaginationProps = reactive({
    page: props.roles.current_page,
    pageSize: props.roles.per_page,
    showSizePicker: true,
    pageSizes: [15, 30, 45, 60, 100],
    itemCount: props.roles.total,
    showQuickJumper: true,
    prefix: (info: PaginationInfo) => h('span', trans(':count in Total', { count: info.itemCount?.toString() || '0' })),
    simple: computed(() => width.value < 640),
    onChange: (page: number) => {
        getData(page, props.roles.per_page)
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
        content: trans('Are you sure you want to delete :name?', { name: trans('Role') }),
        positiveText: trans('Confirm'),
        negativeText: trans('Cancel'),
        onPositiveClick: () => {
            router.delete(route('role.destroy', id), {
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
            :data="roles.data"
            :row-key="(row: Role) => row.id"
            :pagination
            :scroll-x="800"
        />
    </NCard>
</template>
