<script setup lang="ts">
import { useAdminStore } from '@/Stores/admin'
import { NButton } from 'naive-ui'
import { toRef } from 'vue'
import CreateOrEdit from './CreateOrEdit.vue'

const showUserSearchForm = toRef(useAdminStore().value, 'showUserSearchForm')
</script>

<template>
    <div class="flex justify-between items-center">
        <CreateOrEdit />

        <NButton @click="showUserSearchForm = !showUserSearchForm">
            {{ showUserSearchForm ? '收起搜索' : '展开搜索' }}
        </NButton>
    </div>
</template>
