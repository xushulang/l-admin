import { computed, toRefs } from 'vue'
import { useSettingStore } from '@/Stores/setting'
import { languages } from '@/Utils/setting'

export function useLocale() {
    const { lang } = toRefs(useSettingStore().value)

    const locale = computed(() => languages[lang.value as keyof typeof languages].locale)
    const dateLocale = computed(() => languages[lang.value as keyof typeof languages].dateLocale)

    return { locale, dateLocale }
}
