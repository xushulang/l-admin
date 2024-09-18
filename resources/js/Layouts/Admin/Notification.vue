<script setup lang="ts">
import type { NotificationReactive, PopoverInst } from 'naive-ui'
import { Link, usePage } from '@inertiajs/vue3'
import IonNotificationsCircle from '~icons/ion/notifications-circle'
import IonNotificationsOutline from '~icons/ion/notifications-outline'
import { trans } from 'laravel-vue-i18n'
import { NBadge, NButton, NCard, NEllipsis, NEmpty, NIcon, NPopconfirm, NPopover, NSkeleton, NTime, useMessage, useNotification } from 'naive-ui'
import { computed, h, onBeforeMount, ref, useTemplateRef } from 'vue'

const notification = useNotification()
const message = useMessage()

const notificationRef = useTemplateRef<PopoverInst | null>('notificationRef')
const newNotificationRef = ref<NotificationReactive | null>(null)

const notificationCount = ref<number>(usePage().props.notificationCount as number)
const topTenNotifications = ref<any[]>([])

const loading = ref(false)
const loadingCount = computed(() => Math.min(notificationCount.value, 6))

function getTopTenNotifications() {
    loading.value = true

    axios.get(route('notification.top.ten')).then((res) => {
        if (res.data.status === 'success') {
            notificationCount.value = res.data.result.notificationCount
            topTenNotifications.value = res.data.result.topTenNotifications
        }

        loading.value = false
    })
}

function closePopover() {
    notificationRef.value?.setShow(false)
}

function clearNotification() {
    axios.put(route('notification.empty')).then(() => {
        notificationCount.value = 0
        topTenNotifications.value.length = 0
        message.success(trans('Successfully cleared.'))
    })
}

function markAsRead(id: number, index: number) {
    axios.put(route('notification.read', id), { reload: notificationCount.value > 10 }).then((res) => {
        if (notificationCount.value > 10) {
            notificationCount.value = res.data.result.notificationCount
            topTenNotifications.value = res.data.result.topTenNotifications
        }
        else {
            notificationCount.value--
            topTenNotifications.value.splice(index, 1)
        }
    })
}

function handleUpdateShow(value: boolean) {
    if (value) {
        getTopTenNotifications()
    }
}

onBeforeMount(() => {
    window.Echo.private(`App.Models.User.${usePage().props.auth.user.id}`).notification(() => {
        newNotificationRef.value = notification.info({
            content: trans('There are new notifications!'),
            meta: () => {
                return h(
                    NButton,
                    {
                        size: 'small',
                        onClick: () => {
                            getTopTenNotifications()
                            newNotificationRef.value?.destroy()
                            notificationRef.value?.setShow(true)
                        },
                    },
                    () => trans('View Now'),
                )
            },
            onAfterEnter: () => {
                notificationCount.value++
            },
            duration: 2500,
            keepAliveOnHover: true,
        })
    })
})
</script>

<template>
    <NPopover
        ref="notificationRef"
        class="max-h-full max-w-full"
        placement="bottom"
        scrollable
        trigger="click"
        @update:show="handleUpdateShow"
    >
        <template #trigger>
            <div class="flex justify-center items-center">
                <NBadge :value="notificationCount" :max="10" class="cursor-pointer">
                    <NIcon :component="IonNotificationsOutline" :size="18" />
                </NBadge>
            </div>
        </template>

        <NCard
            :content-style="{ 'padding': '0px', 'margin-bottom': '40px' }"
            :footer-style="{
                padding: '0px',
                width: '100%',
                position: 'fixed',
                left: '0px',
                right: '0px',
                bottom: '0px',
            }"
            :bordered="false"
            :segmented="true"
        >
            <div class="w-60 h-60 md:w-64 md:h-64 xl:w-72 xl:h-72">
                <div v-if="notificationCount === 0" class="flex justify-center items-center h-full">
                    <NEmpty :description="$t('No new notifications yet!')" />
                </div>

                <div v-else>
                    <div v-if="loading">
                        <div
                            v-for="(item, index) of loadingCount"
                            :key="index"
                            class="flex items-center max-w-sm p-1 mx-auto space-x-2 rounded-xl"
                        >
                            <div class="shrink-0">
                                <NIcon size="30" color="#f00">
                                    <IonNotificationsCircle />
                                </NIcon>
                            </div>
                            <div class="grow flex justify-between gap-1">
                                <div :class="{ 'w-2/3': loading }">
                                    <NSkeleton text width="60%" />
                                    <NSkeleton text :repeat="1" />
                                </div>
                                <div class="shrink-0" :class="{ 'w-1/3': loading }">
                                    <div class="flex flex-col">
                                        <NSkeleton class="my-0.5" :height="12" :repeat="2" />

                                        <NButton type="primary" size="tiny">
                                            {{ $t('Mark as read') }}
                                        </NButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <div
                            v-for="(item, index) of topTenNotifications"
                            :key="index"
                            class="flex items-center max-w-sm p-1 mx-auto space-x-2 rounded-xl"
                        >
                            <div class="shrink-0">
                                <NIcon :component="IonNotificationsCircle" :size="30" color="#f00" />
                            </div>
                            <div class="grow flex justify-between gap-1">
                                <div>
                                    <div class="text-sm font-medium">
                                        {{ item.data.title }}
                                    </div>
                                    <NEllipsis :line-clamp="1" class="text-gray-500">
                                        {{ item.data.body }}
                                    </NEllipsis>
                                </div>
                                <div class="shrink-0 w-1/3">
                                    <div class="flex flex-col">
                                        <div class="flex flex-col justify-center">
                                            <NTime
                                                class="self-end text-xs text-gray-500"
                                                :time="new Date(item.created_at).getTime()"
                                                format="yyyy-MM-dd"
                                            />
                                            <NTime
                                                class="self-end text-xs text-gray-500"
                                                :time="new Date(item.created_at).getTime()"
                                                format="HH:mm:ss"
                                            />
                                        </div>

                                        <NButton type="primary" size="tiny" @click="markAsRead(item.id, index)">
                                            {{ $t('Mark as read') }}
                                        </NButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <div class="flex justify-evenly py-4">
                    <NPopconfirm @positive-click="clearNotification">
                        <template #trigger>
                            <NButton text>
                                <span>{{ $t('Clear Messages') }}</span>
                            </NButton>
                        </template>

                        <div v-if="notificationCount > 10" class="min-w-max">
                            <span>{{
                                $t('You have :count unread notifications.', {
                                    count: notificationCount.toString(),
                                })
                            }}</span>
                            <span>{{ $t('Are you sure you want to mark all unread notifications as read?') }}</span>
                        </div>
                        <div v-else>
                            {{ $t('Are you sure you want to mark all unread notifications as read?') }}
                        </div>
                    </NPopconfirm>

                    <NButton text @click="closePopover">
                        <Link :href="route('notification.index')">
                            <span>{{ $t('All notifications') }}</span>
                            <span v-if="notificationCount > 10" class="text-red-500">
                                ({{ notificationCount }}条)
                            </span>
                        </Link>
                    </NButton>
                </div>
            </template>
        </NCard>
    </NPopover>
</template>
