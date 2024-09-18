<script setup lang="ts">
import { useSettingStore } from '@/Stores/setting'
import { Head } from '@inertiajs/vue3'
import { NConfigProvider, NDialogProvider, NLayout, NLoadingBarProvider, NMessageProvider, NModalProvider, NNotificationProvider } from 'naive-ui'
import { toRefs } from 'vue'

defineProps<{
    title?: string
    keywords?: string
    description?: string
}>()

const { theme, locale, dateLocale } = toRefs(useSettingStore().value)
</script>

<template>
    <div>
        <Head>
            <title v-if="title">
                {{ title }}
            </title>
            <meta v-if="keywords" name="keywords" :content="keywords">
            <meta v-if="description" name="description" :content="description">
        </Head>

        <NConfigProvider :theme :locale :date-locale>
            <NLoadingBarProvider>
                <NDialogProvider>
                    <NNotificationProvider>
                        <NMessageProvider>
                            <NModalProvider>
                                <NLayout embedded :native-scrollbar="false" class="h-screen">
                                    <main>
                                        <slot />
                                    </main>
                                </NLayout>
                            </NModalProvider>
                        </NMessageProvider>
                    </NNotificationProvider>
                </NDialogProvider>
            </NLoadingBarProvider>
        </NConfigProvider>
    </div>
</template>
