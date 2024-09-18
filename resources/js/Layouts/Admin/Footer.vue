<script setup lang="ts">
const appName = import.meta.env.VITE_APP_NAME
const appYear = import.meta.env.VITE_APP_YEAR
const currentYear = new Date().getFullYear()
</script>

<template>
    <div class="flex justify-between items-center">
        <div class="text-xs sm:text-base flex flex-col lg:flex-row">
            <strong>
                <span>Copyright &copy; {{ `${appYear}-${currentYear}` }}</span>
                <span>
                    <a :href="route('home.index')" target="_blank">{{ appName }}</a>
                </span>
                <span>.</span>
            </strong>
            <span>All rights reserved.</span>
        </div>
        <div class="text-xs sm:text-base">
            <span>Anything you want</span>
        </div>
    </div>
</template>
