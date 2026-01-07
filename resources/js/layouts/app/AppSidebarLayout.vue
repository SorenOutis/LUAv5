<script setup lang="ts">
import AnimationContainer from '@/components/AnimationContainer.vue';
import AppContent from '@/components/AppContent.vue';
import AppShell from '@/components/AppShell.vue';
import AppSidebar from '@/components/AppSidebar.vue';
import AppSidebarHeader from '@/components/AppSidebarHeader.vue';
import ImpersonationBanner from '@/components/ImpersonationBanner.vue';
import type { BreadcrumbItemType } from '@/types';
import { provide, ref } from 'vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

interface ModalState {
    isOpen: boolean;
    title: string;
    message: string;
}

const logoutModal = ref<ModalState>({
    isOpen: false,
    title: 'Logging out...',
    message: 'Please wait while we log you out',
});

provide('logoutModal', logoutModal);

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <!-- Impersonation Banner -->
        <ImpersonationBanner
            :impersonator-name="$page.props.impersonator_name"
        />

        <!-- Logout Modal Overlay -->
        <div
            v-if="logoutModal.isOpen"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm transition-opacity duration-300"
        >
            <!-- Modal -->
            <div
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
            >
                <div
                    class="animate-scale-in relative w-full max-w-sm rounded-2xl border border-border bg-card p-8 shadow-2xl"
                >
                    <!-- Spinner -->
                    <div class="mb-6 flex justify-center">
                        <div class="relative h-16 w-16">
                            <div
                                class="absolute inset-0 rounded-full border-4 border-border/30"
                            />
                            <div
                                class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-primary border-r-primary"
                            />
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-3 text-center">
                        <h2 class="text-2xl font-bold text-foreground">
                            {{ logoutModal.title }}
                        </h2>
                        <p class="text-sm text-muted-foreground">
                            {{ logoutModal.message }}
                        </p>
                    </div>

                    <!-- Loading dots animation -->
                    <div class="mt-6 flex justify-center gap-1.5">
                        <div
                            class="h-2 w-2 animate-bounce rounded-full bg-primary"
                            style="animation-delay: 0s"
                        />
                        <div
                            class="h-2 w-2 animate-bounce rounded-full bg-primary"
                            style="animation-delay: 0.2s"
                        />
                        <div
                            class="h-2 w-2 animate-bounce rounded-full bg-primary"
                            style="animation-delay: 0.4s"
                        />
                    </div>
                </div>
            </div>
        </div>

        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>

        <!-- Global animation container -->
        <AnimationContainer />
    </AppShell>
</template>

<style scoped>
@keyframes scale-in {
    0% {
        opacity: 0;
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-in {
    animation: scale-in 1s ease-out;
}
</style>
