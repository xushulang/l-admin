<script setup lang="ts">
import type { PopoverPlacement } from 'naive-ui'
import AntDesignMenuFoldOutlined from '~icons/ant-design/menu-fold-outlined'
import AntDesignMenuUnfoldOutlined from '~icons/ant-design/menu-unfold-outlined'
import { NIcon, NTooltip } from 'naive-ui'

defineProps<{
    placement?: PopoverPlacement
    collapsed: boolean
}>()

defineEmits(['update:collapsed'])
</script>

<template>
    <div class="flex justify-center items-center">
        <NTooltip :placement>
            <template #trigger>
                <NIcon
                    :component="collapsed ? AntDesignMenuUnfoldOutlined : AntDesignMenuFoldOutlined"
                    :size="18"
                    class="cursor-pointer"
                    @click="() => $emit('update:collapsed', !collapsed)"
                />
            </template>

            <span>{{ collapsed ? $t('Expand') : $t('Collapse') }}</span>
        </NTooltip>
    </div>
</template>
