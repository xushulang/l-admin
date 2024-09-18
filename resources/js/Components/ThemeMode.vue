<script setup lang="ts">
import type { PopoverPlacement } from 'naive-ui'
import { useSettingStore } from '@/Stores/setting'
import { themes } from '@/Utils/setting'
import { useColorMode, useCycleList } from '@vueuse/core'
import { NIcon, NTooltip } from 'naive-ui'
import { toRefs, watchEffect } from 'vue'

defineProps<{ placement?: PopoverPlacement }>()

const { store } = useColorMode({ emitAuto: true })

const { theme } = toRefs(useSettingStore().value)

const { state, next } = useCycleList(Object.keys(themes), { initialValue: store })

watchEffect(() => {
    store.value = state.value as any

    if (theme.value?.name === 'dark') {
        document.documentElement.classList.add('dark')
    }
    else {
        document.documentElement.classList.remove('dark')
    }
})
</script>

<template>
    <div class="flex justify-center items-center">
        <NTooltip :placement>
            <template #trigger>
                <template v-for="(item, index) in themes" :key="index">
                    <NIcon
                        v-if="state === index"
                        :component="item.icon"
                        :size="18"
                        class="cursor-pointer"
                        @click="next()"
                    />
                </template>
            </template>

            <span class="capitalize">{{ state }}</span>
        </NTooltip>
    </div>
</template>
