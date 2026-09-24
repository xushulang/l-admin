<script setup lang="ts">
import { NButton } from 'naive-ui'
import { storeToRefs } from 'pinia'
import { useAdminStore } from '@/Stores/admin'
import CreateOrEdit from './CreateOrEdit.vue'

const { showUserSearchForm } = storeToRefs(useAdminStore())
</script>

<template>
    <div class="flex justify-between items-center">
        <CreateOrEdit />

        <NButton @click="showUserSearchForm = !showUserSearchForm">
            {{ showUserSearchForm ? $t('Collapse :name', { name: $t('Search') }) : $t('Expand :name', { name: $t('Search') }) }}
        </NButton>
    </div>
</template>
