<script setup lang="ts">
import { useSettingStore } from '@/Stores/setting';
import { themes } from '@/Utils/setting';
import { useColorMode, useCycleList } from '@vueuse/core';
import { NButton } from 'naive-ui';
import { watchEffect } from 'vue';

const { system, store } = useColorMode({ emitAuto: true });

const settingStore = useSettingStore();

const { state, next } = useCycleList(Object.keys(themes), { initialValue: store });

watchEffect(() => {
    store.value = state.value as any;
    settingStore.value.theme = themes[store.value === 'auto' ? system.value : store.value].theme;

    if (store.value === 'dark' || (store.value === 'auto' && system.value === 'dark')) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});
</script>

<template>
    <div class="cursor-pointer">
        <template v-for="(item, index) in themes" :key="index">
            <n-button v-if="state === index" size="small" :color="item.color" :render-icon="item.icon" @click="next()" class="capitalize">{{ state }}</n-button>
        </template>
    </div>
</template>
