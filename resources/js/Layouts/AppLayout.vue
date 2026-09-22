<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { NBackTop, NConfigProvider, NDialogProvider, NLayout, NLayoutContent, NLayoutFooter, NLayoutHeader, NLoadingBarProvider, NMessageProvider, NModalProvider, NNotificationProvider } from 'naive-ui'
import { useLocale } from '@/Composables/Common/useLocale'
import { useTheme } from '@/Composables/Common/useTheme'
import AppFooter from './App/Footer.vue'
import AppHeader from './App/Header.vue'

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
                                <NLayout :native-scrollbar="false" class="h-screen">
                                    <NLayoutHeader>
                                        <AppHeader />
                                    </NLayoutHeader>

                                    <NLayoutContent embedded>
                                        <main>
                                            <slot />
                                        </main>
                                    </NLayoutContent>

                                    <NLayoutFooter>
                                        <AppFooter />
                                    </NLayoutFooter>

                                    <NBackTop :bottom="100" />
                                </NLayout>
                            </NModalProvider>
                        </NMessageProvider>
                    </NNotificationProvider>
                </NDialogProvider>
            </NLoadingBarProvider>
        </NConfigProvider>
    </div>
</template>
