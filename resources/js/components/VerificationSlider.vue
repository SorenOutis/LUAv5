<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import { AlertCircle, CheckCircle2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props {
    modelValue: boolean;
    redirectTo?: 'login' | 'register';
}

const props = withDefaults(defineProps<Props>(), {
    redirectTo: 'login',
});

const emit = defineEmits<{
    'update:modelValue': [value: boolean];
    verified: [];
}>();

const isHolding = ref(false);
const holdProgress = ref(0);
const holdDuration = 2000; // 2 seconds to verify
const attemptCount = ref(0);
const maxAttempts = 3;

let holdInterval: ReturnType<typeof setInterval> | null = null;
let holdStartTime: number = 0;

const isVerified = computed(() => holdProgress.value >= 100);
const isFailed = computed(() => attemptCount.value >= maxAttempts);

const startHold = () => {
    if (isVerified.value || isFailed.value) return;

    isHolding.value = true;
    holdProgress.value = 0;
    holdStartTime = Date.now();

    holdInterval = setInterval(() => {
        const elapsed = Date.now() - holdStartTime;
        holdProgress.value = Math.min((elapsed / holdDuration) * 100, 100);

        if (holdProgress.value >= 100) {
            stopHold();
        }
    }, 16); // ~60fps
};

const stopHold = () => {
    if (!isHolding.value) return;

    isHolding.value = false;
    if (holdInterval) {
        clearInterval(holdInterval);
        holdInterval = null;
    }

    if (!isVerified.value) {
        // Reset if not held long enough
        setTimeout(() => {
            attemptCount.value += 1;
            holdProgress.value = 0;
        }, 200);
    }
};

const handleClose = () => {
    resetSlider();
    emit('update:modelValue', false);
};

const handleContinue = async () => {
    emit('verified');
};

const resetSlider = () => {
    holdProgress.value = 0;
    attemptCount.value = 0;
};
</script>

<template>
    <Dialog :open="modelValue" @update:open="handleClose">
        <DialogContent
            class="max-w-md overflow-hidden rounded-2xl border-0 bg-white p-0 shadow-2xl dark:bg-slate-900"
        >
            <!-- Gradient background -->
            <div
                class="absolute inset-0 bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-slate-800 dark:via-slate-900 dark:to-slate-800"
            ></div>

            <!-- Decorative elements -->
            <div
                class="absolute -top-24 -right-24 h-48 w-48 rounded-full bg-gradient-to-br from-amber-300 to-orange-300 opacity-20 blur-3xl dark:opacity-10"
            ></div>
            <div
                class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-gradient-to-tr from-yellow-300 to-amber-300 opacity-20 blur-3xl dark:opacity-10"
            ></div>

            <!-- Content -->
            <div class="relative z-10 space-y-6 p-8">
                <!-- Close button -->
                <button
                    @click="handleClose"
                    class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-gray-600 dark:hover:text-gray-300"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

                <!-- Header -->
                <div class="space-y-2 text-center">
                    <h2
                        class="bg-gradient-to-r from-amber-600 via-orange-500 to-yellow-600 bg-clip-text text-3xl font-bold text-transparent"
                    >
                        Verify Your Identity
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Please hold the button to continue
                    </p>
                </div>

                <!-- Status indicators -->
                <div
                    v-if="!isVerified && !isFailed"
                    class="flex justify-center gap-1"
                >
                    <div
                        v-for="i in maxAttempts"
                        :key="i"
                        :class="[
                            'h-1.5 rounded-full transition-all duration-300',
                            i <= attemptCount
                                ? 'w-2 bg-gray-300 dark:bg-gray-600'
                                : 'w-3 bg-gradient-to-r from-amber-400 to-orange-400',
                        ]"
                    ></div>
                </div>

                <!-- Hold Button Container -->
                <div class="space-y-3">
                    <!-- Main Hold Button -->
                    <button
                        v-if="!isFailed"
                        @mousedown="startHold"
                        @mouseup="stopHold"
                        @mouseleave="stopHold"
                        @touchstart.prevent="startHold"
                        @touchend.prevent="stopHold"
                        :disabled="isVerified || isFailed"
                        :class="[
                            'relative h-14 w-full overflow-hidden rounded-full border-2 shadow-md transition-all duration-300 select-none',
                            isVerified
                                ? 'border-green-400 bg-gradient-to-r from-green-400 via-emerald-400 to-green-500 dark:border-green-600 dark:from-green-600 dark:via-emerald-500 dark:to-green-600'
                                : 'border-gray-200 bg-gradient-to-r from-gray-100 to-gray-50 dark:border-slate-600 dark:from-slate-700 dark:to-slate-600',
                            isHolding && 'scale-[1.02] shadow-lg',
                        ]"
                    >
                        <!-- Progress Fill -->
                        <div
                            v-if="!isVerified"
                            :style="{ width: `${holdProgress}%` }"
                            class="absolute inset-y-0 left-0 bg-gradient-to-r from-amber-400 via-orange-400 to-yellow-400 transition-all duration-75 ease-out dark:from-amber-500 dark:via-orange-500 dark:to-yellow-500"
                        ></div>

                        <!-- Content -->
                        <div
                            class="relative z-10 flex h-full items-center justify-center gap-2"
                        >
                            <!-- Progress Percentage -->
                            <span
                                v-if="!isVerified && holdProgress > 0"
                                class="text-sm font-bold text-orange-700 dark:text-orange-600"
                            >
                                {{ Math.round(holdProgress) }}%
                            </span>
                            <!-- Hold Text -->
                            <span
                                v-if="!isVerified && holdProgress === 0"
                                class="text-sm font-semibold text-gray-600 dark:text-gray-400"
                            >
                                Hold to Verify
                            </span>
                            <!-- Hold Progress Text -->
                            <span
                                v-if="isHolding && !isVerified"
                                class="text-sm font-bold text-orange-700 dark:text-orange-600"
                            >
                                Keep holding...
                            </span>
                            <!-- Check Icon -->
                            <CheckCircle2
                                v-if="isVerified"
                                :size="24"
                                class="text-white"
                                stroke-width="2.5"
                            />
                            <!-- Verified Text -->
                            <span
                                v-if="isVerified"
                                class="font-bold text-white"
                            >
                                Verified!
                            </span>
                        </div>
                    </button>

                    <!-- Failed State -->
                    <div v-else class="space-y-4 text-center">
                        <div class="flex justify-center">
                            <div class="relative">
                                <div
                                    class="absolute inset-0 rounded-full bg-gradient-to-r from-red-400 to-rose-400 opacity-40 blur-lg"
                                ></div>
                                <div
                                    class="relative rounded-full border border-red-200 bg-gradient-to-br from-red-50 to-rose-50 p-3 dark:border-red-800 dark:from-red-950 dark:to-rose-950"
                                >
                                    <AlertCircle
                                        class="h-8 w-8 text-red-600 dark:text-red-400"
                                    />
                                </div>
                            </div>
                        </div>
                        <div>
                            <p
                                class="font-semibold text-red-600 dark:text-red-400"
                            >
                                Too Many Attempts
                            </p>
                            <p
                                class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                            >
                                Please try again later or contact support.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Continue Button -->
                <Transition
                    enter-active-class="animate-in fade-in scale-in duration-300"
                    leave-active-class="animate-out fade-out scale-out duration-200"
                >
                    <Button
                        v-if="isVerified"
                        @click="handleContinue"
                        class="w-full rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 py-3 text-base font-semibold text-white shadow-lg transition-all duration-300 hover:from-green-700 hover:to-emerald-700 hover:shadow-xl dark:from-green-600 dark:to-emerald-600 dark:hover:from-green-700 dark:hover:to-emerald-700"
                    >
                        Continue
                    </Button>
                </Transition>

                <!-- Security message -->
                <div
                    class="text-center text-xs text-gray-500 dark:text-gray-400"
                >
                    <p>🔒 This verification helps keep your account secure</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
