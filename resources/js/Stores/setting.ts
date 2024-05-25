import { languages, themes } from '@/Utils/setting';
import { createGlobalState, useColorMode, useStorage } from '@vueuse/core';
import { getActiveLanguage } from 'laravel-vue-i18n';

const mode = useColorMode({
    emitAuto: true,
});

export const useSettingStore = createGlobalState(() =>
    useStorage('setting', {
        theme: themes[mode.value].theme,
        locale: languages[getActiveLanguage() as keyof typeof languages].locale,
        dateLocale: languages[getActiveLanguage() as keyof typeof languages].dateLocale,
    })
);
