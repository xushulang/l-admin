<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const options = [
    {
        key: 0,
        name: 'Home Page',
        route: 'home.index',
    },
];
</script>

<template>
    <div class="flex items-center gap-3 sm:gap-6">
        <div
            v-for="item in options"
            :key="item.key"
            class="hover:border-b-[3px] h-16 leading-[4rem] hover:border-blue-500 inline-block group"
            :class="{ 'border-b-[3px] border-blue-500': route().current(item.route) }"
        >
            <Link
                :href="route(item.route ? item.route : 'home.online.index')"
                class="font-medium group-hover:text-blue-500 inline-block"
                :class="{ 'text-blue-500': route().current(item.route) }"
            >
                {{ $t(item.name) }}
            </Link>
        </div>
    </div>
</template>
