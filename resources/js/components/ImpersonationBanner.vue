<script setup lang="ts">
import { LogOut, UserCircle } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props {
    impersonatorName?: string;
}

const props = withDefaults(defineProps<Props>(), {
    impersonatorName: '',
});

const isImpersonating = computed(
    () => !!props.impersonatorName && props.impersonatorName.length > 0,
);

const isStopping = ref(false);

const stopImpersonation = () => {
    isStopping.value = true;

    // Use window.location with full URL for guaranteed redirect
    window.location.href = '/impersonate/exit';
};
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform -translate-y-full opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform -translate-y-full opacity-0"
    >
        <div
            v-if="isImpersonating"
            class="fixed top-0 right-0 left-0 z-50 w-full bg-amber-500 text-white shadow-lg"
        >
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <UserCircle class="size-5" />
                        <div class="text-sm font-medium">
                            <span class="font-bold">Impersonation Mode:</span>
                            You are viewing as
                            <span class="font-semibold">{{
                                $page.props.auth.user.name
                            }}</span>
                            (Logged in as {{ impersonatorName }})
                        </div>
                    </div>
                    <button
                        @click="stopImpersonation"
                        :disabled="isStopping"
                        class="flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-amber-600 transition-colors hover:bg-amber-50 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <LogOut v-if="!isStopping" class="size-4" />
                        <svg
                            v-else
                            class="size-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <span>{{
                            isStopping ? 'Stopping...' : 'Stop Impersonation'
                        }}</span>
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>
<style scoped>
/* Push content down when impersonation banner is visible */
:deep(body:has([class~*='fixed top-0'])) {
    padding-top: 60px;
}
</style>
