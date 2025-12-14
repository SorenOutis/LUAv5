<script setup lang="ts">
interface Props {
    customClass?: string;
}

defineProps<Props>();
</script>

<template>
    <div :class="['animate-pulse rounded-md bg-slate-200 dark:bg-slate-800', customClass]" />
</template>
