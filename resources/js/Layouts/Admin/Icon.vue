<script setup lang="ts">
import type { Depth } from 'naive-ui/es/icon/src/Icon'
import CarbonUser from '~icons/carbon/user'
import FluentPersonBoard20Regular from '~icons/fluent/person-board-20-regular'
import IonCubeOutline from '~icons/ion/cube-outline'
import IonNotificationsOutline from '~icons/ion/notifications-outline'
import MaterialSymbolsCircleOutline from '~icons/material-symbols/circle-outline'
import MaterialSymbolsDashboardOutline from '~icons/material-symbols/dashboard-outline'
import MaterialSymbolsFingerprint from '~icons/material-symbols/fingerprint'
import { NIcon } from 'naive-ui'

defineOptions({
    components: {
        NIcon,
        MaterialSymbolsCircleOutline,
        MaterialSymbolsDashboardOutline,
        IonCubeOutline,
        MaterialSymbolsFingerprint,
        FluentPersonBoard20Regular,
        CarbonUser,
        IonNotificationsOutline,
    },
})

defineProps<{
    icon: string
    color?: string
    depth?: Depth
    size?: number
}>()
</script>

<template>
    <div class="flex justify-center items-center">
        <NIcon :color :depth :size>
            <component :is="icon" />
        </NIcon>
    </div>
</template>
