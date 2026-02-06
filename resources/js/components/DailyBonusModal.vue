<script setup lang="ts">
import { ref, computed } from 'vue';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import Button from '@/components/ui/button/Button.vue';
import axios from 'axios';
import { CheckCircle2, X, Gift, LogIn } from 'lucide-vue-next';
import { useToast } from '@/composables/useToast';

interface Props {
    open: boolean;
    hasReceivedToday: boolean;
    xpAmount: number;
}

const props = withDefaults(defineProps<Props>(), {
    open: false,
    hasReceivedToday: false,
    xpAmount: 20,
});

const emit = defineEmits<{
    'update:open': [value: boolean];
    'bonus-claimed': [result: any];
    'closed': [result: any];
}>();

const { xp: addXPToast } = useToast();

const isLoading = ref(false);
const claimError = ref('');
const claimSuccess = ref(false);
const bonusData = ref<any>(null);
const streakData = ref<any>(null);

const isOpen = computed({
    get: () => props.open,
    set: (value) => emit('update:open', value),
});

const handleClaimBonus = async () => {
    if (props.hasReceivedToday) return;

    isLoading.value = true;
    claimError.value = '';
    claimSuccess.value = false;

    try {
        const response = await axios.post('/api/daily-bonus/claim');
        if (response.data.success) {
            claimSuccess.value = true;
            bonusData.value = response.data;
            streakData.value = response.data.streak;
            emit('bonus-claimed', response.data);

            // Show toast notification with XP earned
            addXPToast(
                props.xpAmount,
                'Daily Login Bonus'
            );

            // Close modal after 2.5 seconds
            setTimeout(() => {
                isOpen.value = false;
            }, 2500);
        } else {
            claimError.value = response.data.message || 'Failed to claim bonus';
        }
    } catch (error: any) {
        claimError.value = error.response?.data?.message || 'An error occurred while claiming the bonus';
    } finally {
        isLoading.value = false;
    }
};

const handleClose = () => {
    if (!isLoading.value) {
        const data = bonusData.value;
        const streak = streakData.value;
        claimError.value = '';
        claimSuccess.value = false;
        bonusData.value = null;
        streakData.value = null;
        isOpen.value = false;
        emit('closed', { bonusData: data, streakData: streak });
    }
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="handleClose">
        <DialogContent class="max-w-xs border border-primary/30 shadow-2xl overflow-hidden md:max-w-xs w-80">
            <!-- Clean background - using theme variables -->
            <div class="absolute inset-0 bg-card"></div>
            
            <!-- Subtle accent line at top -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-primary/20"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-3 py-6 px-5">
                <!-- Success State -->
                <transition enter-active-class="animate-in fade-in scale-in duration-300"
                    leave-active-class="animate-out fade-out scale-out duration-200">
                    <div v-if="claimSuccess" class="flex flex-col items-center gap-2 w-full">
                        <div class="relative">
                            <div class="relative bg-primary/20 rounded-full p-3 border border-primary/30">
                                <CheckCircle2 class="w-8 h-8 text-primary" stroke-width="1.5" />
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-primary mb-1">
                                Bonus Claimed!
                            </h3>
                            <p class="text-muted-foreground text-xs">
                                You earned <span class="font-semibold text-primary">{{ xpAmount }} XP</span>
                            </p>
                            <p class="text-muted-foreground text-xs mt-1">
                                Total: <span class="text-foreground font-semibold">{{ bonusData?.total_xp }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Default State -->
                    <div v-else-if="!hasReceivedToday" class="flex flex-col items-center gap-3 w-full">
                        <!-- Icon -->
                        <div class="relative">
                            <div class="relative bg-primary/20 rounded-xl p-4">
                                <Gift class="w-8 h-8 text-primary" />
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="text-center space-y-1">
                            <h2 class="text-xl font-semibold text-foreground">
                                Daily Bonus
                            </h2>
                            <p class="text-muted-foreground text-xs">
                                Claim your reward
                            </p>
                        </div>

                        <!-- XP Display -->
                        <div class="w-full rounded-lg px-4 py-3 border bg-primary/10 border-primary/20">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-3xl font-bold text-primary">
                                    +{{ xpAmount }}
                                </span>
                                <span class="text-lg font-semibold text-foreground">XP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Already Claimed State -->
                    <div v-else class="flex flex-col items-center gap-2 w-full">
                        <div class="relative bg-muted/20 rounded-xl p-4">
                            <CheckCircle2 class="w-8 h-8 text-muted-foreground" />
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold mb-1 text-foreground">
                                Already Claimed
                            </h3>
                            <p class="text-muted-foreground text-xs">
                                You've claimed your bonus today.
                            </p>
                            <p class="text-muted-foreground text-xs mt-1">
                                Come back tomorrow!
                            </p>
                        </div>
                    </div>
                </transition>

                <!-- Error Message -->
                <transition enter-active-class="animate-in fade-in slide-in-from-top duration-300"
                    leave-active-class="animate-out fade-out slide-out-to-top duration-200">
                    <div v-if="claimError"
                        class="w-full border border-destructive/30 rounded-lg p-3 shadow-sm bg-destructive/10">
                        <div class="flex items-start gap-2">
                            <X class="w-4 h-4 text-destructive flex-shrink-0 mt-0.5" />
                            <div>
                                <p class="font-semibold text-xs text-destructive">{{ claimError }}</p>
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Buttons -->
                <div class="w-full flex gap-2 mt-3">
                    <Button v-if="!hasReceivedToday && !claimSuccess" @click="handleClaimBonus" :disabled="isLoading"
                        class="flex-1 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 disabled:opacity-60 text-xs">
                        <span v-if="!isLoading">Claim</span>
                        <span v-else class="flex items-center justify-center gap-1">
                            <span class="inline-block animate-spin">⟳</span>
                        </span>
                    </Button>
                    <Button @click="handleClose" :disabled="isLoading"
                        class="flex-1 font-semibold py-2 rounded-lg border bg-muted hover:bg-muted/80 text-muted-foreground border-border transition-all duration-300 disabled:opacity-60 text-xs">
                        {{ claimSuccess || hasReceivedToday ? 'Close' : 'Skip' }}
                    </Button>
                </div>

                <!-- Streak Reminder -->
                <div class="text-muted-foreground text-center text-xs mt-1.5 flex items-center justify-center gap-1">
                    <LogIn class="w-3 h-3" />
                    <p>Log in daily for rewards</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
