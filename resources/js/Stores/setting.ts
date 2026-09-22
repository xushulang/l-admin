import { createGlobalState, useStorage } from '@vueuse/core'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
import { ref, watch } from 'vue'

export const useSettingStore = createGlobalState(() => {
    const lang = ref(getActiveLanguage())

    watch(
        () => lang.value,
        (value) => {
            loadLanguageAsync(value)
        },
    )

    return useStorage('setting', { lang })
})
