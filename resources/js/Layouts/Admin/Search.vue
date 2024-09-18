<script setup lang="ts">
import type { PopoverPlacement, TreeSelectOverrideNodeClickBehavior } from 'naive-ui'
import { Link, router } from '@inertiajs/vue3'
import IonSearchOutline from '~icons/ion/search-outline'
import { wTrans } from 'laravel-vue-i18n'
import { NIcon, NModal, NTooltip, NTreeSelect } from 'naive-ui'
import { h, onMounted, ref } from 'vue'

const props = defineProps<{
    placement?: PopoverPlacement
    menus: any[]
}>()

const showModal = ref(false)

const menuOptions = ref<any[]>()

const search = ref<any>()

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

function renderMenuLabel({ option }: any) {
    if (option.route) {
        return h(Link, { href: route(option.route), class: 'py-2 block' }, { default: () => option.label })
    }

    return h('div', { class: 'cursor-pointer py-2' }, option.label)
}

const override: TreeSelectOverrideNodeClickBehavior = ({ option }) => {
    if (option.children) {
        return 'toggleExpand'
    }

    return 'default'
}

function handleUpdateSearch(value: string, option: any) {
    search.value = value

    if (!value) {
        return
    }

    if (option.route) {
        router.get(route(option.route))
    }

    showModal.value = false
}

onMounted(() => {
    menuOptions.value = generatorMenu(props.menus)
})
</script>

<template>
    <div>
        <div class="flex justify-center items-center">
            <NTooltip :placement>
                <template #trigger>
                    <NIcon :component="IonSearchOutline" :size="18" class="cursor-pointer" @click="showModal = true" />
                </template>

                <span class="capitalize">{{ $t('Search') }}</span>
            </NTooltip>
        </div>

        <NModal v-model:show="showModal" preset="card" :title="$t('Search')" class="max-w-2xl fixed inset-x-0 top-40">
            <NTreeSelect
                :value="search"
                default-expand-all
                :consistent-menu-width="false"
                placement="bottom"
                check-strategy="child"
                :placeholder="$t('Search')"
                :options="menuOptions"
                key-field="name"
                :render-label="renderMenuLabel"
                filterable
                clearable
                :override-default-node-click-behavior="override"
                :node-props="() => ({ class: 'flex items-center' })"
                @update:value="handleUpdateSearch"
            />
        </NModal>
    </div>
</template>

<style scoped>
:deep(.n-base-selection-input) {
    @apply focus:ring-transparent;
}
</style>
