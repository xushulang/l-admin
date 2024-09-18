<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { computed } from 'vue'

defineProps<{ collapsed: boolean }>()

const appName = computed(() => (import.meta.env.VITE_APP_NAME.length <= 4 ? import.meta.env.VITE_APP_NAME : '') + trans('Manage System'))
const appLogo = import.meta.env.VITE_APP_LOGO || 'https://ui-avatars.com/api/?name=LAdmin&uppercase=false&font-size=0.2&color=7F9CF5&background=EBF4FF&length=6'
</script>

<template>
    <Link :href="route('admin.index')">
        <div v-show="collapsed">
            <img :src="appLogo" class="max-h-14 p-2">
        </div>

        <div v-show="!collapsed" class="flex items-center">
            <img :src="appLogo" class="max-h-14 p-2">
            <span class="text-xl text-nowrap">{{ appName }}</span>
        </div>
    </Link>
</template>
