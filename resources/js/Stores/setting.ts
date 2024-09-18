import { languages, themes } from '@/Utils/setting'
import { createGlobalState, useColorMode, useStorage } from '@vueuse/core'
import { getActiveLanguage, loadLanguageAsync } from 'laravel-vue-i18n'
import { ref, watch } from 'vue'

export const useSettingStore = createGlobalState(() => {
    const setting = ref({
        lang: getActiveLanguage(),
        theme: themes[useColorMode({ emitAuto: true }).value].theme,
        locale: languages[getActiveLanguage() as keyof typeof languages].locale,
        dateLocale: languages[getActiveLanguage() as keyof typeof languages].dateLocale,
    })

    watch(
        () => setting.value.lang,
        (value) => {
            loadLanguageAsync(value).then(() => {
                setting.value.locale = languages[value as keyof typeof languages].locale
                setting.value.dateLocale = languages[value as keyof typeof languages].dateLocale
            })
        },
    )

    watch(
        () => useColorMode({ emitAuto: true }),
        ({ system, store }) => {
            setting.value.theme = themes[store.value === 'auto' ? system.value : store.value].theme
        },
        { immediate: true },
    )

    return useStorage('setting', setting)
})
