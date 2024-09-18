import type { Component } from 'vue'
import { pickBy } from 'lodash'
import { NIcon } from 'naive-ui'
import { h } from 'vue'

export function renderIcon(icon: Component) {
    return h(NIcon, { component: icon })
}

export function handleSearchParams(params: any) {
    if (params.page === 1) {
        params.page = null
    }

    if (params.per_page === 15) {
        params.per_page = null
    }

    return pickBy(params)
}
