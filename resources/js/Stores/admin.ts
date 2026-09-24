import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAdminStore = defineStore(
    'admin',
    () => {
        const showRoleSearchForm = ref(false)
        const showUserSearchForm = ref(false)

        return {
            showRoleSearchForm,
            showUserSearchForm,
        }
    },
    {
        persist: true,
    },
)
