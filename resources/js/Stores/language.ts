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
        persist: {
            key: 'lang',
            serializer: {
                serialize: state => state.lang,
                deserialize: value => ({ lang: value }),
            },
            afterHydrate: ({ store: piniaStore }) => {
                piniaStore.$persist()
            },
        },
    },
)
