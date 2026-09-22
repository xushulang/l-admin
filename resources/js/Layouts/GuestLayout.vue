<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { NConfigProvider, NDialogProvider, NLayout, NLoadingBarProvider, NMessageProvider, NModalProvider, NNotificationProvider } from 'naive-ui'
import { useLocale } from '@/Composables/Common/useLocale'
import { useTheme } from '@/Composables/Common/useTheme'

defineProps<{
    title?: string
    keywords?: string
    description?: string
}>()

const { naiveTheme, themeOverrides } = useTheme()
const { locale, dateLocale } = useLocale()
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

        <NConfigProvider :theme="naiveTheme" :theme-overrides="themeOverrides" :locale :date-locale>
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
