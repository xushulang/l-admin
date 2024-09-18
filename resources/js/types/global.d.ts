import type { PageProps as InertiaPageProps } from '@inertiajs/core'
import type { AxiosInstance } from 'axios'
import type { route as ziggyRoute } from 'ziggy-js'
import type { PageProps as AppPageProps } from './'

declare global {
    interface Window {
        axios: AxiosInstance
        Pusher: any
        Echo: Echo
    }

    let axios: AxiosInstance
    let route: typeof ziggyRoute
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}
