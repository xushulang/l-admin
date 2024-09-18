<script setup lang="ts">
import { useAdminStore } from '@/Stores/admin'
import { NButton } from 'naive-ui'
import { toRef } from 'vue'
import CreateOrEdit from './CreateOrEdit.vue'

const showRoleSearchForm = toRef(useAdminStore().value, 'showRoleSearchForm')
</script>

<template>
    <div class="flex justify-between items-center">
        <CreateOrEdit />

        <NButton @click="showRoleSearchForm = !showRoleSearchForm">
            {{ showRoleSearchForm ? '收起搜索' : '展开搜索' }}
        </NButton>
    </div>
</template>
