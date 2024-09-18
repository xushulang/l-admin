<script setup lang="ts">
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { trans } from 'laravel-vue-i18n'
import { NButton, NCard, NCheckbox, NPagination, NPopconfirm, NSkeleton, NTime, useMessage } from 'naive-ui'
import { ref, toRefs } from 'vue'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    notifications: any
    notificationCount: number
}>()

const message = useMessage()

const { notifications, notificationCount } = toRefs(props)

const unreadOnly = ref(false)
const loading = ref(false)
const showIndex = ref<number | null>()

function getData(page: number | null, pageSize: number | null) {
    loading.value = true

    router.reload({
        data: {
            unread_only: unreadOnly.value,
            page,
            per_page: pageSize,
        },
        onSuccess: () => {
            loading.value = false
        },
    })
}

function markAsRead(id: number, index: number) {
    showIndex.value = index

    if (!notifications.value.data[index].read_at) {
        axios.put(route('notification.read', id)).then((response) => {
            notifications.value.data[index].read_at = response.data.read_at
            message.success(trans('Marked as read'))
        })
    }
}

function allMarkAsRead() {
    axios.put(route('notification.empty')).then(() => {
        notifications.value.data.map((item: any) => {
            item.read_at = new Date().toLocaleString()

            return item
        })
        message.success(trans('Marked all as read'))
    })
}

function handlePageChange(value: number) {
    getData(value, notifications.value.per_page)
}

function handlePageSizeChange(value: number) {
    getData(null, value)
}
</script>

<template>
    <NCard size="small">
        <template #header>
            <div class="flex items-center gap-2 sm:gap-4">
                <span>{{ $t('Notification List') }}</span>
                <div class="flex items-center">
                    <NCheckbox :checked="unreadOnly" :label="$t('Unread Only')" />
                    <span class="text-red-500">
                        {{ $t(':count Notifications', { count: notificationCount.toString() }) }}
                    </span>
                </div>
            </div>
        </template>

        <template #header-extra>
            <NPopconfirm @positive-click="allMarkAsRead">
                <template #trigger>
                    <NButton type="success" size="small">
                        {{ $t('Mark all as read') }}
                    </NButton>
                </template>
                {{ $t('Are you sure you want to mark all unread notifications as read?') }}
            </NPopconfirm>
        </template>

        <template v-for="(item, index) in notifications.data" :key="index">
            <NCard class="mt-2 hover:bg-gray-300/20" size="small" @click="markAsRead(item.id, index)">
                <template #header>
                    <div class="flex items-center gap-2">
                        <div>{{ item.data.title }}</div>
                        <div v-if="!item.read_at" class="text-xs" :class="{ 'text-red-500': !item.read_at }">
                            {{ $t('Unread') }}
                        </div>
                    </div>
                </template>

                <template #header-extra>
                    <NTime :time="new Date(item.created_at).getTime()" />
                </template>

                <NSkeleton v-if="notifications.data.length === 0" text :repeat="6" />

                <div v-if="showIndex === index" v-html="item.data.body" />

                <template v-if="showIndex === index" #footer>
                    <div v-if="item.data.action" class="flex justify-between items-center">
                        <NButton type="default" size="small">
                            <Link :href="item.data.action.url">
                                {{ item.data.action.title }}
                            </Link>
                        </NButton>
                    </div>
                </template>
            </NCard>
        </template>

        <NPagination
            v-if="notifications.data.length > 1"
            v-model:page="notifications.current_page"
            v-model:page-size="notifications.per_page"
            class="pt-4"
            :disabled="loading"
            show-size-picker
            :page-sizes="[15, 30, 45, 60, 100]"
            :page-count="notifications.last_page"
            show-quick-jumper
            @update:page="handlePageChange"
            @update:page-size="handlePageSizeChange"
        />
    </NCard>
</template>
