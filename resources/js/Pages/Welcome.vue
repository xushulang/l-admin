<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

defineOptions({ layout: AppLayout })

defineProps<{
    canLogin?: boolean
    canRegister?: boolean
    laravelVersion?: string
    phpVersion?: string
}>()

const height = ref(0)

onMounted(() => {
    height.value
        = window.innerHeight
        - (document.querySelector('.n-layout-header')?.clientHeight || 0)
        - (document.querySelector('.n-layout-footer')?.clientHeight || 0)
})
</script>

<template>
    <div>
        <Head :title="$t('Welcome')" />

        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <img
                id="background"
                class="absolute -left-20 top-0 max-w-[877px]"
                src="https://laravel.com/assets/img/welcome/background.svg"
            >

            <main :style="{ height: `${height}px` }">
                <div class="flex flex-col justify-center items-center gap-2 sm:gap-4 h-full">
                    <div class="text-3xl sm:text-5xl">
                        LAdmin
                    </div>
                    <div>Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})</div>
                </div>
            </main>
        </div>
    </div>
</template>
