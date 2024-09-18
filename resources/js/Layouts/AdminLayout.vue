<script setup lang="ts">
import type { User } from '@/types'
import { useSettingStore } from '@/Stores/setting'
import { Head, useRemember } from '@inertiajs/vue3'
import { useWindowSize } from '@vueuse/core'
import { NConfigProvider, NDialogProvider, NDrawer, NDrawerContent, NLayout, NLayoutContent, NLayoutFooter, NLayoutHeader, NLayoutSider, NLoadingBarProvider, NMessageProvider, NModalProvider, NNotificationProvider } from 'naive-ui'
import { computed, onBeforeMount, ref, toRefs } from 'vue'
import AdminFooter from './Admin/Footer.vue'
import AdminHeader from './Admin/Header.vue'
import AdminLogo from './Admin/Logo.vue'
import AdminMenu from './Admin/Menu.vue'

const props = defineProps<{
    title?: string
    currentLocation: any[]
    menus: any[]
    notificationCount?: number
    auth: {
        user: User
    }
    lang: string
}>()

const title = computed(() => props.title || props.currentLocation[props.currentLocation.length - 1].title)

const { theme, locale, dateLocale } = toRefs(useSettingStore().value)

const collapsed = ref(false)
const active = ref(false)

onBeforeMount(() => {
    collapsed.value = Number(useWindowSize().width) < 960
})

const menus = useRemember(props.menus, 'menus')
</script>

<template>
    <div>
        <Head :title="title" />

        <NConfigProvider :theme :locale :date-locale>
            <NLoadingBarProvider>
                <NDialogProvider>
                    <NNotificationProvider>
                        <NMessageProvider>
                            <NModalProvider>
                                <NLayout class="min-h-screen" position="absolute" has-sider>
                                    <NLayoutSider
                                        class="!w-60 hidden sm:block"
                                        bordered
                                        collapse-mode="width"
                                        :collapsed="collapsed"
                                        @collapse="collapsed = true"
                                        @expand="collapsed = false"
                                    >
                                        <NLayout class="h-14" position="absolute" bordered>
                                            <!-- Logo -->
                                            <AdminLogo class="h-14 flex justify-center items-center" :collapsed />
                                        </NLayout>

                                        <NLayout
                                            class="!top-14"
                                            position="absolute"
                                            bordered
                                            :native-scrollbar="false"
                                        >
                                            <!-- Menu -->
                                            <AdminMenu v-model:collapsed="collapsed" :current-location :menus />
                                        </NLayout>
                                    </NLayoutSider>

                                    <NLayout embedded>
                                        <NLayoutHeader position="absolute" class="h-14" bordered>
                                            <!-- Header -->
                                            <AdminHeader
                                                v-model:collapsed="collapsed"
                                                v-model:active="active"
                                                class="h-14"
                                                :menus
                                                :current-location
                                            />
                                        </NLayoutHeader>

                                        <NLayoutContent
                                            class="!top-16 !bottom-16 mx-2"
                                            position="absolute"
                                            :native-scrollbar="false"
                                        >
                                            <!-- Page Content -->
                                            <main>
                                                <slot />
                                            </main>
                                        </NLayoutContent>

                                        <NLayoutFooter class="h-14 px-2 sm:px-4" position="absolute" bordered>
                                            <!-- Footer -->
                                            <AdminFooter class="h-14" />
                                        </NLayoutFooter>
                                    </NLayout>
                                </NLayout>

                                <NDrawer v-model:show="active" class="!w-60" placement="left">
                                    <NDrawerContent>
                                        <NLayout class="h-14" position="absolute" embedded bordered>
                                            <!-- Logo -->
                                            <AdminLogo class="h-14 flex justify-center items-center" :collapsed />
                                        </NLayout>

                                        <NLayout
                                            class="!top-14"
                                            position="absolute"
                                            embedded
                                            bordered
                                            :native-scrollbar="false"
                                        >
                                            <!-- Menu -->
                                            <AdminMenu v-model:collapsed="collapsed" :current-location :menus />
                                        </NLayout>
                                    </NDrawerContent>
                                </NDrawer>
                            </NModalProvider>
                        </NMessageProvider>
                    </NNotificationProvider>
                </NDialogProvider>
            </NLoadingBarProvider>
        </NConfigProvider>
    </div>
</template>
