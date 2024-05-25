import { NIcon, darkTheme, dateEnUS, dateZhCN, enUS, lightTheme, zhCN } from 'naive-ui';
import Moon from '@vicons/ionicons5/Moon';
import SunnySharp from '@vicons/ionicons5/SunnySharp';
import DesktopSharp from '@vicons/ionicons5/DesktopSharp';
import { h } from 'vue';

export const themes = {
    light: {
        theme: lightTheme,
        color: '#18A058',
        icon: () => h(NIcon, null, () => h(SunnySharp)),
    },
    dark: {
        theme: darkTheme,
        color: '#4B5563',
        icon: () => h(NIcon, null, () => h(Moon)),
    },
    auto: {
        theme: null,
        color: '#2080f0',
        icon: () => h(NIcon, null, () => h(DesktopSharp)),
    },
};

export const languages = {
    en: {
        locale: enUS,
        dateLocale: dateEnUS,
    },
    zh_CN: {
        locale: zhCN,
        dateLocale: dateZhCN,
    },
};
