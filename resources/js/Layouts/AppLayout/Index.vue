<script setup lang="ts">
import { useSettingStore } from '@/Stores/setting';
import { Head } from '@inertiajs/vue3';
import {
    NBackTop,
    NConfigProvider,
    NDialogProvider,
    NLayout,
    NLayoutContent,
    NLayoutFooter,
    NLayoutHeader,
    NLoadingBarProvider,
    NMessageProvider,
    NModalProvider,
    NNotificationProvider,
} from 'naive-ui';
import Header from './Header.vue';
import Footer from './Footer.vue';
import { toRefs } from 'vue';

defineProps<{
    title?: string;
    keywords?: string;
    description?: string;
}>();

const { theme, locale, dateLocale } = toRefs(useSettingStore().value);
</script>

<template>
    <div>
        <Head>
            <title v-if="title">{{ title }}</title>
            <meta v-if="keywords" name="keywords" :content="keywords" />
            <meta v-if="description" name="description" :content="description" />
        </Head>

        <n-config-provider :theme :locale :dateLocale>
            <n-loading-bar-provider>
                <n-dialog-provider>
                    <n-notification-provider>
                        <n-message-provider>
                            <n-modal-provider>
                                <n-layout :native-scrollbar="false" class="h-screen">
                                    <n-layout-header>
                                        <Header />
                                    </n-layout-header>

                                    <n-layout-content embedded>
                                        <main>
                                            <slot></slot>
                                        </main>
                                    </n-layout-content>

                                    <n-layout-footer>
                                        <Footer />
                                    </n-layout-footer>

                                    <n-back-top :bottom="100" />
                                </n-layout>
                            </n-modal-provider>
                        </n-message-provider>
                    </n-notification-provider>
                </n-dialog-provider>
            </n-loading-bar-provider>
        </n-config-provider>
    </div>
</template>
