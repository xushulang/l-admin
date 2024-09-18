<script setup lang="ts">
import { useSettingStore } from '@/Stores/setting'
import IonLanguageOutline from '~icons/ion/language-outline'
import { loadLanguageAsync } from 'laravel-vue-i18n'
import { NDropdown, NIcon } from 'naive-ui'
import { computed, reactive, toRefs } from 'vue'

const { lang } = toRefs(useSettingStore().value)

const options = reactive([
    {
        label: '中文',
        key: 'zh_CN',
        disabled: computed(() => lang.value === 'zh_CN'),
    },
    {
        label: 'English',
        key: 'en',
        disabled: computed(() => lang.value === 'en'),
    },
])

function handleSelect(key: string) {
    loadLanguageAsync(key).then(() => (lang.value = key))
}
</script>

<template>
    <div class="flex justify-center items-center">
        <NDropdown trigger="hover" :options="options" @select="handleSelect">
            <NIcon :component="IonLanguageOutline" :size="18" class="cursor-pointer" />
        </NDropdown>
    </div>
</template>
