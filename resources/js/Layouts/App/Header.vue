<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import ThemeMode from '@/Components/ThemeMode.vue'
import { NDivider } from 'naive-ui'
import Menu from './Menu.vue'
import UserCenter from './UserCenter.vue'
</script>

<template>
    <div>
        <div class="max-w-7xl m-auto flex justify-between items-center gap-3 sm:gap-6 h-16 px-1 sm:px-2 xl:px-0">
            <div class="flex items-center gap-3 sm:gap-6">
                <ApplicationLogo class="h-10 fill-current text-gray-500" />

                <Menu />
            </div>

            <div class="flex items-center gap-3 sm:gap-6">
                <ThemeMode />

                <UserCenter :user="$page.props.auth.user" />
            </div>
        </div>

        <NDivider class="!m-0" />
    </div>
</template>
