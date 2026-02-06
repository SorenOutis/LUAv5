<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';

interface Props {
    texts: string[];
    interval?: number;
    mainClassName?: string;
    splitLevelClassName?: string;
}

const props = withDefaults(defineProps<Props>(), {
    interval: 2000,
    mainClassName: '',
    splitLevelClassName: '',
});

const currentIndex = ref(0);
const currentText = computed(() => props.texts[currentIndex.value]);
const isTransitioning = ref(false);

let intervalId: NodeJS.Timeout | null = null;

const rotate = () => {
    isTransitioning.value = true;
    setTimeout(() => {
        currentIndex.value = (currentIndex.value + 1) % props.texts.length;
        isTransitioning.value = false;
    }, 300);
};

onMounted(() => {
    intervalId = setInterval(rotate, props.interval);
});

onUnmounted(() => {
    if (intervalId) clearInterval(intervalId);
});
</script>

<template>
    <span :class="mainClassName">
        <span :class="[splitLevelClassName, isTransitioning ? 'opacity-0' : 'opacity-100']"
            style="transition: opacity 0.3s ease-out">
            {{ currentText }}
        </span>
    </span>
</template>
