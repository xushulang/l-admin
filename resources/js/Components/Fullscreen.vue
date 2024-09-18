<script setup lang="ts">
import type { PopoverPlacement } from 'naive-ui'
import { useFullscreen } from '@vueuse/core'
import AntDesignFullscreenExitOutlined from '~icons/ant-design/fullscreen-exit-outlined'
import AntDesignFullscreenOutlined from '~icons/ant-design/fullscreen-outlined'
import { NIcon, NTooltip } from 'naive-ui'

defineProps<{ placement?: PopoverPlacement }>()

const { isFullscreen, toggle } = useFullscreen()
</script>

<template>
    <div class="flex justify-center items-center">
        <NTooltip :placement>
            <template #trigger>
                <NIcon
                    :component="isFullscreen ? AntDesignFullscreenExitOutlined : AntDesignFullscreenOutlined"
                    :size="18"
                    class="cursor-pointer"
                    @click="toggle"
                />
            </template>

            <span>{{ isFullscreen ? $t('Exit Fullscreen') : $t('Fullscreen') }}</span>
        </NTooltip>
    </div>
</template>
