<script setup lang="ts">
import type { User } from '@/types'
import { Link, router } from '@inertiajs/vue3'
import AntDesignLogoutOutlined from '~icons/ant-design/logout-outlined'
import AntDesignProfileOutlined from '~icons/ant-design/profile-outlined'
import { trans } from 'laravel-vue-i18n'
import { NAvatar, NDropdown, NIcon, useDialog, useMessage } from 'naive-ui'
import { h, ref } from 'vue'

defineProps<{ user: User | null }>()

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
        label: () => h(Link, { href: route('profile.edit') }, () => trans('Profile')),
        key: 0,
        icon: () => h(NIcon, { component: AntDesignProfileOutlined }),
    },
    {
        label: () => h('div', trans('Log Out')),
        key: 1,
        icon: () => h(NIcon, { component: AntDesignLogoutOutlined }),
    },
])

function handleSelect(key: number) {
    if (key === 1) {
        logout()
    }
}
</script>

<template>
    <div class="flex items-center cursor-pointer">
        <div v-if="user">
            <NDropdown trigger="hover" :options="options" @select="handleSelect">
                <NAvatar round :src="user.profile_photo_url" />
            </NDropdown>
        </div>

        <div v-else class="flex items-center gap-2 sm:gap-4">
            <Link :href="route('login')" class="hover:text-blue-500">
                {{ $t('Log In') }}
            </Link>

            <Link v-if="route().has('register')" :href="route('register')" class="hover:text-blue-500">
                {{ $t('Register') }}
            </Link>
        </div>
    </div>
</template>
