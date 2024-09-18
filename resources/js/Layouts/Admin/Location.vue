<script setup lang="ts">
import { useWindowSize } from '@vueuse/core'

defineProps<{ currentLocation: any[] }>()

const { width } = useWindowSize()
</script>

<template>
    <div v-if="width > 640" class="flex items-center">
        <div v-for="(value, key) in currentLocation.slice(0, -1)" :key="key" class="flex">
            <p>{{ $t(value.title) }}</p>
            <p class="px-2">
                /
            </p>
        </div>
        <p>{{ $t(currentLocation[currentLocation.length - 1].title) }}</p>
    </div>
</template>
