<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { NResult } from 'naive-ui'
</script>

<template>
    <div>
        <Head title="403" />

        <div class="h-screen flex justify-center items-center">
            <NResult
                status="403"
                :title="`403${$t('http-statuses.403')}`"
                :description="$t('Some doors will always be closed to you.')"
            />
        </div>
    </div>
</template>
