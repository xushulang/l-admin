<script setup lang="ts">
import Collapse from '@/Components/Collapse.vue'
import Fullscreen from '@/Components/Fullscreen.vue'
import Language from '@/Components/Language.vue'
import ReloadPage from '@/Components/ReloadPage.vue'
import ThemeMode from '@/Components/ThemeMode.vue'
import IonMenuOutline from '~icons/ion/menu-outline'
import { NIcon } from 'naive-ui'
import AdminLocation from './Location.vue'
import Notification from './Notification.vue'
import Search from './Search.vue'
import UserCenter from './UserCenter.vue'

defineProps<{
    collapsed: boolean
    active: boolean
    menus: any[]
    currentLocation: any[]
}>()

const emit = defineEmits(['update:collapsed', 'update:active'])
</script>

<template>
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4 mx-4">
            <div class="block sm:hidden">
                <div class="flex justify-center items-center">
                    <NIcon
                        :component="IonMenuOutline"
                        :size="18"
                        class="cursor-pointer"
                        @click="() => $emit('update:active', !active)"
                    />
                </div>
            </div>

            <div class="hidden sm:block">
                <Collapse :collapsed="collapsed" @update:collapsed="emit('update:collapsed', $event)" />
            </div>

            <AdminLocation :current-location="currentLocation" />
        </div>

        <div class="flex items-center gap-4 mx-4">
            <Search :menus />

            <Notification />

            <ReloadPage />

            <Fullscreen />

            <Language />

            <ThemeMode />

            <UserCenter />
        </div>
    </div>
</template>
