<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import AntDesignHomeOutlined from '~icons/ant-design/home-outlined'
import AntDesignLogoutOutlined from '~icons/ant-design/logout-outlined'
import AntDesignUserOutlined from '~icons/ant-design/user-outlined'
import { trans } from 'laravel-vue-i18n'
import { NAvatar, NDropdown, NIcon, useDialog, useMessage } from 'naive-ui'
import { h, ref } from 'vue'

const dialog = useDialog()
const message = useMessage()

function logout() {
    dialog.info({
        title: trans('Prompt'),
        content: trans('Are you sure you want to log out?'),
        positiveText: trans('Confirm'),
        negativeText: trans('Cancel'),
        onPositiveClick: () => {
            router.post(
                route('logout'),
                {},
                {
                    onFinish: () => {
                        message.success(trans('Logged Out'))
                    },
                },
            )
        },
    })
}

const options = ref([
    {
        label: () => h('a', { href: route('home.index'), target: '_blank' }, trans('Home Page')),
        key: 0,
        icon: () => h(NIcon, { component: AntDesignHomeOutlined }),
    },
    {
        label: () => h(Link, { href: route('profile.edit') }, () => trans('User Setting')),
        key: 1,
        icon: () => h(NIcon, { component: AntDesignUserOutlined }),
    },
    {
        label: () => h('div', trans('Log Out')),
        key: 2,
        icon: () => h(NIcon, { component: AntDesignLogoutOutlined }),
    },
])

function handleSelect(key: number) {
    if (key === 2) {
        logout()
    }
}
</script>

<template>
    <div class="flex items-center cursor-pointer">
        <NDropdown trigger="hover" :options="options" @select="handleSelect">
            <NAvatar round :src="$page.props.auth.user.profile_photo_url" />
        </NDropdown>
    </div>
</template>
