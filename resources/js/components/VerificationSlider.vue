<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import { AlertCircle, CheckCircle2 } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

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
const cooldownTime = 30; // 30 seconds cooldown
const cooldownRemaining = ref(0);

let holdInterval: ReturnType<typeof setInterval> | null = null;
let holdStartTime: number = 0;
let cooldownInterval: ReturnType<typeof setInterval> | null = null;

const STORAGE_KEY = 'verification_attempts';
const STORAGE_COOLDOWN_KEY = 'verification_cooldown';

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
            saveAttemptsToStorage();
            
            if (attemptCount.value >= maxAttempts) {
                startCooldown();
            }
            holdProgress.value = 0;
        }, 200);
    }
};

const startCooldown = () => {
    const cooldownExpiry = Date.now() + cooldownTime * 1000;
    localStorage.setItem(STORAGE_COOLDOWN_KEY, cooldownExpiry.toString());
    
    cooldownRemaining.value = cooldownTime;
    
    if (cooldownInterval) {
        clearInterval(cooldownInterval);
    }
    
    cooldownInterval = setInterval(() => {
        cooldownRemaining.value -= 1;
        if (cooldownRemaining.value <= 0) {
            if (cooldownInterval) {
                clearInterval(cooldownInterval);
                cooldownInterval = null;
            }
            // Reset attempts when cooldown expires
            attemptCount.value = 0;
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(STORAGE_COOLDOWN_KEY);
        }
    }, 1000);
};

const checkCooldown = () => {
    const cooldownExpiry = localStorage.getItem(STORAGE_COOLDOWN_KEY);
    if (!cooldownExpiry) return false;
    
    const expiryTime = parseInt(cooldownExpiry);
    const now = Date.now();
    
    if (now < expiryTime) {
        cooldownRemaining.value = Math.ceil((expiryTime - now) / 1000);
        startCooldown();
        return true;
    } else {
        localStorage.removeItem(STORAGE_COOLDOWN_KEY);
        return false;
    }
};

const saveAttemptsToStorage = () => {
    localStorage.setItem(STORAGE_KEY, attemptCount.value.toString());
};

const loadAttemptsFromStorage = () => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) {
        attemptCount.value = parseInt(stored);
    }
};

const resetCooldown = () => {
    if (cooldownInterval) {
        clearInterval(cooldownInterval);
        cooldownInterval = null;
    }
    cooldownRemaining.value = 0;
    localStorage.removeItem(STORAGE_COOLDOWN_KEY);
};

const handleClose = () => {
    // Don't reset cooldown/attempts when closing - only reset UI state
    holdProgress.value = 0;
    isHolding.value = false;
    if (holdInterval) {
        clearInterval(holdInterval);
        holdInterval = null;
    }
    emit('update:modelValue', false);
};

const handleContinue = async () => {
    // Only reset when verification is successful
    resetSlider();
    emit('verified');
};

const resetSlider = () => {
    holdProgress.value = 0;
    attemptCount.value = 0;
    localStorage.removeItem(STORAGE_KEY);
    resetCooldown();
};

const initializeModal = () => {
    loadAttemptsFromStorage();
    if (checkCooldown()) {
        // Cooldown is still active
    }
};

onMounted(() => {
    initializeModal();
});

watch(() => props.modelValue, (newValue) => {
    if (newValue) {
        // Modal is opening, reinitialize to check for active cooldown
        initializeModal();
    }
});
</script>

<template>
    <Dialog :open="modelValue" @update:open="handleClose">
        <DialogContent
            class="max-w-md overflow-hidden border bg-card p-0 shadow-lg"
        >
            <!-- Content -->
            <div class="relative space-y-6 p-8">
                <!-- Header -->
                <div class="space-y-2 text-center">
                    <h2
                        class="text-2xl font-bold text-foreground"
                    >
                        Verify Your Identity
                    </h2>
                    <p class="text-sm text-muted-foreground">
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
                            'h-2 w-2 rounded-full transition-all duration-300',
                            i <= attemptCount
                                ? 'bg-border'
                                : 'bg-primary',
                        ]"
                    ></div>
                </div>

                <!-- Hold Button Container -->
                <div class="space-y-3">
                    <!-- Main Hold Button -->
                    <button
                        v-if="!isFailed && cooldownRemaining === 0"
                        @mousedown="startHold"
                        @mouseup="stopHold"
                        @mouseleave="stopHold"
                        @touchstart.prevent="startHold"
                        @touchend.prevent="stopHold"
                        :disabled="isVerified"
                        :class="[
                            'relative h-12 w-full overflow-hidden rounded-md border transition-all duration-300 select-none font-medium',
                            isVerified
                                ? 'border-ring bg-primary text-primary-foreground'
                                : 'border-input bg-input text-foreground hover:bg-accent',
                            isHolding && 'scale-[1.02] shadow-md',
                        ]"
                    >
                        <!-- Progress Fill -->
                        <div
                            v-if="!isVerified"
                            :style="{ width: `${holdProgress}%` }"
                            class="absolute inset-y-0 left-0 bg-primary/30 transition-all duration-75 ease-out"
                        ></div>

                        <!-- Content -->
                        <div
                            class="relative z-10 flex h-full items-center justify-center gap-2"
                        >
                            <!-- Progress Percentage -->
                            <span
                                v-if="!isVerified && holdProgress > 0"
                                class="text-sm font-bold"
                            >
                                {{ Math.round(holdProgress) }}%
                            </span>
                            <!-- Hold Text -->
                            <span
                                v-if="!isVerified && holdProgress === 0"
                                class="text-sm font-semibold"
                            >
                                Hold to Verify
                            </span>
                            <!-- Hold Progress Text -->
                            <span
                                v-if="isHolding && !isVerified"
                                class="text-sm font-bold"
                            >
                                Keep holding...
                            </span>
                            <!-- Check Icon -->
                            <CheckCircle2
                                v-if="isVerified"
                                :size="24"
                                stroke-width="2.5"
                            />
                            <!-- Verified Text -->
                            <span
                                v-if="isVerified"
                                class="font-bold"
                            >
                                Verified!
                            </span>
                        </div>
                    </button>

                    <!-- Cooldown State -->
                    <div v-if="isFailed && cooldownRemaining > 0" class="space-y-4 text-center">
                        <div class="flex justify-center">
                            <div class="rounded-lg border border-destructive/30 bg-destructive/5 p-3">
                                <AlertCircle
                                    class="h-8 w-8 text-destructive"
                                />
                            </div>
                        </div>
                        <div>
                            <p
                                class="font-semibold text-destructive"
                            >
                                Too Many Attempts
                            </p>
                            <p
                                class="mt-2 text-sm text-muted-foreground"
                            >
                                Please wait before trying again
                            </p>
                            <p
                                class="mt-3 text-2xl font-bold text-primary"
                            >
                                {{ cooldownRemaining }}s
                            </p>
                        </div>
                    </div>

                    <!-- Failed State (No Cooldown) -->
                    <div v-else-if="isFailed && cooldownRemaining === 0" class="space-y-4 text-center">
                        <div class="flex justify-center">
                            <div class="rounded-lg border border-destructive/30 bg-destructive/5 p-3">
                                <AlertCircle
                                    class="h-8 w-8 text-destructive"
                                />
                            </div>
                        </div>
                        <div>
                            <p
                                class="font-semibold text-destructive"
                            >
                                Too Many Attempts
                            </p>
                            <p
                                class="mt-1 text-sm text-muted-foreground"
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
                        variant="default"
                        size="lg"
                        class="w-full"
                    >
                        Continue
                    </Button>
                </Transition>

                <!-- Security message -->
                <div
                    class="text-center text-xs text-muted-foreground"
                >
                    <p>🔒 This verification helps keep your account secure</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
