<script setup lang="ts">
import { withDefaults, defineProps } from 'vue';

interface Props {
    count?: number;
}

withDefaults(defineProps<Props>(), {
    count: 1,
});
</script>

<template>
    <div :key="i" v-for="i in count" class="space-y-3 rounded-lg border border-slate-200 p-4 dark:border-slate-800">
        <Skeleton class="h-6 w-3/4" />
        <Skeleton class="h-4 w-full" />
        <Skeleton class="h-4 w-5/6" />
        <div class="flex gap-2 pt-2">
            <Skeleton class="h-8 w-20" />
            <Skeleton class="h-8 w-20" />
        </div>
    </div>
</template>

<script lang="ts">
import Skeleton from './Skeleton.vue';
</script>
