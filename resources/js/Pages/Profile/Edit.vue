<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout/Index.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineOptions({ layout: AppLayout });

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <div>
        <Head :title="$t('Profile')" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-2 sm:gap-4">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-3xl" />

                <UpdatePasswordForm class="max-w-3xl" />

                <DeleteUserForm class="max-w-3xl" />
            </div>
        </div>
    </div>
</template>
