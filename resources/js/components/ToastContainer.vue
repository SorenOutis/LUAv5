<script setup lang="ts">
import { useToast } from '@/composables/useToast';
import Toast from '@/components/Toast.vue';

const { toasts, removeToast } = useToast();
</script>

<template>
    <div class="fixed top-4 right-4 z-40 flex flex-col gap-3 max-w-md pointer-events-none">
        <transition-group name="toast" tag="div" class="flex flex-col gap-3">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                class="pointer-events-auto"
            >
                <Toast
                    :toast="toast"
                    @close="removeToast(toast.id)"
                />
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(30px);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
}

.toast-move {
    transition: transform 0.3s ease;
}
</style>
