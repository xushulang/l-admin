<script setup lang="ts">
import { NButton } from 'naive-ui'
import { toRef } from 'vue'
import { useAdminStore } from '@/Stores/admin'
import CreateOrEdit from './CreateOrEdit.vue'

const showRoleSearchForm = toRef(useAdminStore().value, 'showRoleSearchForm')
</script>

<template>
    <div class="flex justify-between items-center">
        <CreateOrEdit />

        <NButton @click="showRoleSearchForm = !showRoleSearchForm">
            {{ showRoleSearchForm ? $t('Collapse :name', { name: $t('Search') }) : $t('Expand :name', { name: $t('Search') }) }}
        </NButton>
    </div>
</template>
