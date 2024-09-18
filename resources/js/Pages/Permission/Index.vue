<script setup lang="ts">
import type { DataTableColumns } from 'naive-ui'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { trans, wTrans } from 'laravel-vue-i18n'
import { NCard, NDataTable } from 'naive-ui'
import { ref, toRef } from 'vue'

defineOptions({ layout: AdminLayout })

const props = defineProps<{ permissions: any[] }>()

interface RowData {
    id: number
    name: string
    title: string
    key: string
    level: number
    icon?: string
    route?: string
    children?: RowData[]
}

const permissions = toRef(props, 'permissions')

const columns: DataTableColumns<RowData> = [
    {
        title: () => trans('Permission Title'),
        key: 'title',
    },
    {
        title: () => trans('Permission Name'),
        key: 'name',
    },
    {
        title: () => trans('Route'),
        key: 'route',
    },
]

function handlePermission(array: any[], baseKey: string = '') {
    return array.map((element, index) => {
        const key = `${baseKey}${element.level}${index}`

        const currentPermission: any = {
            id: element.id,
            name: element.name,
            title: wTrans(element.title),
            key,
            icon: element.icon,
            route: element.route,
            level: element.level,
        }

        if (element.children && element.children.length > 0) {
            currentPermission.children = handlePermission(element.children, key)
        }

        return currentPermission
    })
}

const data = ref(handlePermission(permissions.value))
</script>

<template>
    <NCard :bordered="false" size="small">
        <NDataTable :columns="columns" :data="data" :row-key="(row: RowData) => row.key" default-expand-all />
    </NCard>
</template>
