<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { NResult } from 'naive-ui'
</script>

<template>
    <div>
        <Head title="404" />

        <div class="h-screen flex justify-center items-center">
            <NResult
                status="404"
                :title="`404${$t('http-statuses.404')}`"
                :description="$t('Life always has a bit of absurdity.')"
            />
        </div>
    </div>
</template>
