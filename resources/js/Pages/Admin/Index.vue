<script setup lang="ts">
import type { DataTableColumns } from 'naive-ui'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { trans, wTrans } from 'laravel-vue-i18n'
import { NCard, NDataTable } from 'naive-ui'
import { ref } from 'vue'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    serverInfo: {
        'Server': string
        'Laravel Framework': string
        'Environment': string
        'PHP': string
        'MySQL': string
    }
}>()

const columns: DataTableColumns<{ title: string, key: string, align: string }> = [
    {
        title: () => trans('Information'),
        key: 'name',
        align: 'center',
    },
    {
        title: () => trans('Version'),
        key: 'value',
        align: 'center',
    },
]

const data = ref([
    {
        key: 0,
        name: wTrans('Server System'),
        value: props.serverInfo.Server,
    },
    {
        key: 0,
        name: 'Laravel Framework',
        value: props.serverInfo['Laravel Framework'],
    },
    {
        key: 0,
        name: wTrans('Environment'),
        value: props.serverInfo.Environment,
    },
    {
        key: 0,
        name: wTrans('PHP Version'),
        value: props.serverInfo.PHP,
    },
    {
        key: 0,
        name: wTrans('MySQL Version'),
        value: props.serverInfo.MySQL,
    },
])
</script>

<template>
    <NCard :bordered="false" size="small">
        <div>
            <div class="p-2 sm:p-4">
                {{ $t('A beautiful day has begun!') }}
            </div>

            <div class="p-2 sm:p-4">
                <div class="pb-4">
                    {{ $t('System Information') }}
                </div>
                <NDataTable :columns="columns" :data="data" :single-line="false" />
            </div>
        </div>
    </NCard>
</template>
