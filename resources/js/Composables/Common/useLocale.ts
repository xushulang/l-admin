import { storeToRefs } from 'pinia'
import { computed } from 'vue'
import { useLanguageStore } from '@/Stores/language'
import { languages } from '@/Utils/setting'

export function useLocale() {
    const { lang } = storeToRefs(useLanguageStore())

    const locale = computed(() => languages[lang.value as keyof typeof languages].locale)
    const dateLocale = computed(() => languages[lang.value as keyof typeof languages].dateLocale)

    return { locale, dateLocale }
}
