import { useColorMode } from '@vueuse/core'
import { computed } from 'vue'
import { themes } from '@/Utils/setting'

export function useTheme() {
    const { store, system } = useColorMode({ storageKey: 'theme' })

    const theme = computed(() => (store.value === 'auto' ? system.value : store.value))

    const naiveTheme = computed(() => themes[theme.value].theme)

    const themeOverrides = computed(() => themes[theme.value].themeOverrides)

    return { naiveTheme, themeOverrides }
}
