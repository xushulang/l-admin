import { useColorMode } from '@vueuse/core'
import { computed } from 'vue'
import { themes } from '@/Utils/setting'

export function useTheme() {
    const { store, system } = useColorMode({ storageKey: 'theme' })

    const theme = computed(() => {
        if (!Object.keys(themes).includes(store.value)) {
            store.value = 'auto'
        }

        if (store.value === 'auto') {
            return system.value
        }

        return store.value
    })

    const naiveTheme = computed(() => themes[theme.value].theme)

    const themeOverrides = computed(() => themes[theme.value].themeOverrides)

    return { naiveTheme, themeOverrides }
}
