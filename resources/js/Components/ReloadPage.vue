<script setup lang="ts">
import type { PopoverPlacement } from 'naive-ui'
import { router } from '@inertiajs/vue3'
import AntDesignReloadOutlined from '~icons/ant-design/reload-outlined'
import { trans } from 'laravel-vue-i18n'
import { NIcon, NTooltip, useMessage } from 'naive-ui'

defineProps<{ placement?: PopoverPlacement }>()

const message = useMessage()

function reloadPage() {
    router.reload({
        onSuccess: () => {
            message.success(trans('Successfully refreshed.'))
        },
        onError: () => {
            message.error(trans('Failed to refresh.'))
        },
    })
}
</script>

<template>
    <div class="flex justify-center items-center">
        <NTooltip :placement>
            <template #trigger>
                <NIcon :component="AntDesignReloadOutlined" :size="18" class="cursor-pointer" @click="reloadPage" />
            </template>

            <span>{{ $t('Refresh') }}</span>
        </NTooltip>
    </div>
</template>
