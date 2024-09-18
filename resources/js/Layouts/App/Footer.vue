<script setup lang="ts">
const appName = import.meta.env.VITE_APP_NAME
const appYear = import.meta.env.VITE_APP_YEAR
const currentYear = new Date().getFullYear()
const appBeianNumber = import.meta.env.VITE_APP_BEIAN_NUMBER
const appBeianHref = import.meta.env.VITE_APP_BEIAN_HREF
</script>

<template>
    <div
        class="flex flex-col justify-center items-center py-2 sm:py-4 bg-zinc-700 dark:bg-transparent text-white dark:text-gray-300"
    >
        <div class="text-xs sm:text-base flex flex-col items-center lg:flex-row">
            <strong>
                <span>Copyright &copy; {{ `${appYear}-${currentYear}` }}</span>
                <span>
                    <a :href="route('home.index')" target="_blank">{{ appName }}</a>
                </span>
                <span>.</span>
            </strong>
            <span>All rights reserved.</span>
        </div>

        <div v-if="appBeianNumber" class="text-xs sm:text-base">
            <a :href="appBeianHref">{{ appBeianNumber }}</a>
        </div>
    </div>
</template>
