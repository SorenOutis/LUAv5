<script setup lang="ts">
import { withDefaults, defineProps } from 'vue';

interface Props {
    count?: number;
}

withDefaults(defineProps<Props>(), {
    count: 4,
});
</script>

<template>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div :key="i" v-for="i in count" class="rounded-lg border border-slate-200 p-4 dark:border-slate-800">
            <Skeleton class="mb-3 h-4 w-24" />
            <Skeleton class="h-8 w-16" />
        </div>
    </div>
</template>

<script lang="ts">
import Skeleton from './Skeleton.vue';
</script>
