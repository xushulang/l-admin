<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import LogoutOutlined from '@vicons/antd/LogoutOutlined';
import ProfileOutlined from '@vicons/antd/ProfileOutlined';
import { h, ref } from 'vue';
import { trans, wTrans } from 'laravel-vue-i18n';
import { NAvatar, NDropdown, NIcon, useDialog, useMessage } from 'naive-ui';

defineProps<{
    user: any;
}>();

const dialog = useDialog();
const message = useMessage();

const avatarOptions = ref<any>([
    {
        label: wTrans('Profile'),
        key: 1,
        icon: () => h(NIcon, { component: ProfileOutlined }),
        route: 'profile.edit',
    },
    {
        label: wTrans('Log Out'),
        key: 2,
        icon: () => h(NIcon, { component: LogoutOutlined }),
        route: null,
    },
]);

const logout = () => {
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
                        message.success(trans('Logged Out'));
                    },
                }
            );
        },
    });
};

const avatarSelect = (key: number) => {
    if (key === 2) {
        logout();
    }
};

const renderAvatarLabel = (option: any) => {
    if (option.route) {
        return h(Link, { href: route(option.route) }, () => option.label);
    } else {
        return h('span', option.label);
    }
};
</script>

<template>
    <div class="cursor-pointer flex items-center">
        <div v-if="user">
            <n-dropdown
                trigger="hover"
                :options="avatarOptions"
                :render-label="renderAvatarLabel"
                @select="avatarSelect"
            >
                <n-avatar round :src="user.profile_photo_url" />
            </n-dropdown>
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
