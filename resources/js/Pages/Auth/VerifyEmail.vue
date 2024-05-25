<script setup lang="ts">
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { NButton } from 'naive-ui';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <div>
        <Head :title="$t('Email Verification')" />

        <div class="min-h-screen flex justify-center items-center">
            <div class="w-full max-w-[28rem] flex flex-col justify-center gap-3">
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    {{
                        $t(
                            "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another."
                        )
                    }}
                </div>

                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400" v-if="verificationLinkSent">
                    {{
                        $t(
                            'A new verification link has been sent to the email address you provided during registration.'
                        )
                    }}
                </div>

                <form @submit.prevent="submit">
                    <div class="mt-4 flex items-center justify-between">
                        <n-button type="primary" :disabled="form.processing" @click="submit">
                            {{ $t('Resend Verification Email') }}
                        </n-button>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            {{ $t('Log Out') }}
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
