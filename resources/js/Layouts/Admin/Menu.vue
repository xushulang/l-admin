<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { wTrans } from 'laravel-vue-i18n'
import { NMenu } from 'naive-ui'
import { h, onBeforeMount, ref, toRef } from 'vue'
import AdminIcon from './Icon.vue'

const props = defineProps<{
    collapsed: boolean
    currentLocation: any[]
    menus: any[]
}>()

const menuOptions = ref<any[]>()

function generatorMenu(params: any[]) {
    return params.map((item: any) => {
        const currentMenu: any = {
            label: wTrans(item.title),
            iconName: item.icon,
            name: item.name,
            route: item.route,
        }

        if (item.children && item.children.length > 0) {
            currentMenu.children = generatorMenu(item.children)
        }

        return currentMenu
    })
}

function renderMenuLabel(option: any) {
    if (option.route) {
        return h(Link, { href: route(option.route) }, { default: () => option.label })
    }

    return option.label
}

function renderMenuIcon(option: any) {
    return h(AdminIcon, {
        icon: option.iconName,
        size: 18,
    })
}

const currentMenuLocation = toRef(props, 'currentLocation')

const selectedKey = ref<string | null>()
const openKeys = ref<string[]>([])

function updateSelectedKeyAndOpenKey() {
    if (currentMenuLocation.value.length > 0) {
        selectedKey.value = currentMenuLocation.value[currentMenuLocation.value.length - 1].name

        if (currentMenuLocation.value[0].name !== openKeys.value[0]) {
            openKeys.value = []
        }

        currentMenuLocation.value.forEach((element, index) => {
            if (index !== currentMenuLocation.value.length - 1 && !openKeys.value.includes(element.name)) {
                openKeys.value.push(element.name)
            }
        })
    }
    else {
        selectedKey.value = null
        openKeys.value = []
    }
}

onBeforeMount(() => {
    menuOptions.value = generatorMenu(props.menus)

    updateSelectedKeyAndOpenKey()
})
</script>

<template>
    <NMenu
        accordion
        :options="menuOptions"
        :default-value="selectedKey"
        :collapsed
        :collapsed-icon-size="18"
        :default-expanded-keys="openKeys"
        key-field="name"
        :render-label="renderMenuLabel"
        :render-icon="renderMenuIcon"
        class="max-w-60"
    />
</template>
