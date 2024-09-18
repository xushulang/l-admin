import { createGlobalState, useStorage } from '@vueuse/core'

export const useAdminStore = createGlobalState(() =>
    useStorage('admin', {
        showRoleSearchForm: false,
        showUserSearchForm: false,
    }),
)
