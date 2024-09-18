<script setup lang="ts">
import type { ResultProps } from 'naive-ui'
import { Head } from '@inertiajs/vue3'
import { NResult } from 'naive-ui'

defineProps<{
    status: ResultProps['status']
    title: string
    description: string
    tdk: {
        title?: string
        keywords?: string
        description?: string
    }
}>()
</script>

<template>
    <div>
        <Head>
            <title>{{ tdk.title ? `${title} - ${tdk.title}` : title }}</title>
            <meta name="keywords" :content="tdk.keywords">
            <meta name="description" :content="tdk.description">
        </Head>

        <div class="h-screen flex justify-center items-center">
            <NResult :status="status" :title="title" :description="description" />
        </div>
    </div>
</template>
