import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useLanguageStore = defineStore(
    'language',
    () => {
        const lang = ref(getActiveLanguage())

        watch(
            () => lang.value,
            (value) => {
                loadLanguageAsync(value)
            },
        )

        return { lang }
    },
    {
        persist: true,
    },
)
