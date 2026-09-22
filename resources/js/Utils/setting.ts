import type { GlobalThemeOverrides } from 'naive-ui'
import { darkTheme, dateEnUS, dateZhCN, enUS, lightTheme, zhCN } from 'naive-ui'
import IonDesktopSharp from '~icons/ion/desktop-sharp'
import IonMoon from '~icons/ion/moon'
import IonSunnySharp from '~icons/ion/sunny-sharp'

export const lightThemeOverrides: GlobalThemeOverrides = {}

export const darkThemeOverrides: GlobalThemeOverrides = {}

export const themes = {
    light: {
        theme: lightTheme,
        icon: IonSunnySharp,
        themeOverrides: lightThemeOverrides,
    },
    dark: {
        theme: darkTheme,
        icon: IonMoon,
        themeOverrides: darkThemeOverrides,
    },
    auto: {
        theme: null,
        icon: IonDesktopSharp,
        themeOverrides: null,
    },
}

export const languages = {
    en: {
        locale: enUS,
        dateLocale: dateEnUS,
    },
    zh_CN: {
        locale: zhCN,
        dateLocale: dateZhCN,
    },
}
