import IonDesktopSharp from '~icons/ion/desktop-sharp'
import IonMoon from '~icons/ion/moon'
import IonSunnySharp from '~icons/ion/sunny-sharp'
import { darkTheme, dateEnUS, dateZhCN, enUS, lightTheme, zhCN } from 'naive-ui'

export const themes = {
    light: {
        theme: lightTheme,
        icon: IonSunnySharp,
    },
    dark: {
        theme: darkTheme,
        icon: IonMoon,
    },
    auto: {
        theme: null,
        icon: IonDesktopSharp,
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
