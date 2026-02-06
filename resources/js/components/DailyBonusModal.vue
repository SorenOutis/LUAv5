<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import Button from '@/components/ui/button/Button.vue';
import axios from 'axios';
import { CheckCircle2, X } from 'lucide-vue-next';
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
const isDarkMode = ref(false);

// Detect system theme preference
const checkSystemTheme = () => {
    isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
};

// Listen for system theme changes
const setupThemeListener = () => {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    mediaQuery.addEventListener('change', (e) => {
        isDarkMode.value = e.matches;
    });
    return mediaQuery;
};

let themeMediaQuery: any = null;

onMounted(() => {
    checkSystemTheme();
    themeMediaQuery = setupThemeListener();
});

onUnmounted(() => {
    if (themeMediaQuery) {
        themeMediaQuery.removeEventListener('change', checkSystemTheme);
    }
});

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
        <DialogContent class="max-w-xs border-0 shadow-2xl overflow-hidden md:max-w-xs w-80">
            <!-- Clean background - theme aware -->
            <div :class="isDarkMode ? 'bg-gradient-to-br from-slate-900 to-slate-800' : 'bg-white'" class="absolute inset-0"></div>
            
            <!-- Subtle accent line at top -->
            <div :class="isDarkMode ? 'bg-orange-500/20' : 'bg-orange-100'" class="absolute top-0 left-0 right-0 h-1"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-3 py-6 px-5">
                <!-- Success State -->
                <transition
                    enter-active-class="animate-in fade-in scale-in duration-300"
                    leave-active-class="animate-out fade-out scale-out duration-200"
                >
                    <div v-if="claimSuccess" class="flex flex-col items-center gap-2 w-full">
                        <div class="relative">
                            <div class="relative bg-green-100 rounded-full p-3 border border-green-200">
                                <CheckCircle2 class="w-8 h-8 text-green-600" stroke-width="1.5" />
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-green-600 mb-1">
                                Bonus Claimed!
                            </h3>
                            <p :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs">
                                You earned <span class="font-semibold text-orange-600">{{ xpAmount }} XP</span>
                            </p>
                            <p :class="isDarkMode ? 'text-gray-500' : 'text-gray-500'" class="text-xs mt-1">
                                Total: <span :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'" class="font-semibold">{{ bonusData?.total_xp }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Default State -->
                    <div v-else-if="!hasReceivedToday" class="flex flex-col items-center gap-3 w-full">
                        <!-- Icon -->
                        <div class="relative">
                            <div :class="isDarkMode ? 'bg-orange-500/10' : 'bg-orange-50'" class="relative rounded-xl p-4">
                                <span class="text-4xl drop-shadow-sm">🎁</span>
                            </div>
                        </div>

                        <!-- Title -->
                        <div class="text-center space-y-1">
                            <h2 :class="isDarkMode ? 'text-white' : 'text-gray-900'" class="text-xl font-semibold">
                                Daily Bonus
                            </h2>
                            <p :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs">
                                Claim your reward
                            </p>
                        </div>

                        <!-- XP Display -->
                        <div :class="isDarkMode ? 'bg-orange-500/10 border-orange-500/20' : 'bg-orange-50 border-orange-200'" class="w-full rounded-lg px-4 py-3 border">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-3xl font-bold text-orange-600">
                                    +{{ xpAmount }}
                                </span>
                                <span :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'" class="text-lg font-semibold">XP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Already Claimed State -->
                    <div v-else class="flex flex-col items-center gap-2 w-full">
                        <div :class="isDarkMode ? 'bg-gray-500/10' : 'bg-gray-100'" class="relative rounded-xl p-4">
                            <span class="text-4xl">✓</span>
                        </div>
                        <div class="text-center">
                            <h3 :class="isDarkMode ? 'text-white' : 'text-gray-900'" class="text-lg font-semibold mb-1">
                                Already Claimed
                            </h3>
                            <p :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs">
                                You've claimed your bonus today.
                            </p>
                            <p :class="isDarkMode ? 'text-gray-500' : 'text-gray-500'" class="text-xs mt-1">
                                Come back tomorrow!
                            </p>
                        </div>
                    </div>
                </transition>

                <!-- Error Message -->
                <transition
                    enter-active-class="animate-in fade-in slide-in-from-top duration-300"
                    leave-active-class="animate-out fade-out slide-out-to-top duration-200"
                >
                    <div v-if="claimError" :class="isDarkMode ? 'bg-red-500/10 border-red-500/30' : 'bg-red-50 border-red-200'" class="w-full border rounded-lg p-3 shadow-sm">
                        <div class="flex items-start gap-2">
                            <X class="w-4 h-4 text-red-600 flex-shrink-0 mt-0.5" />
                            <div>
                                <p :class="isDarkMode ? 'text-red-400' : 'text-red-700'" class="font-semibold text-xs">{{ claimError }}</p>
                            </div>
                        </div>
                    </div>
                </transition>

                <!-- Buttons -->
                <div class="w-full flex gap-2 mt-3">
                    <Button
                        v-if="!hasReceivedToday && !claimSuccess"
                        @click="handleClaimBonus"
                        :disabled="isLoading"
                        class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 disabled:opacity-60 text-xs"
                    >
                        <span v-if="!isLoading">Claim</span>
                        <span v-else class="flex items-center justify-center gap-1">
                            <span class="inline-block animate-spin">⟳</span>
                        </span>
                    </Button>
                    <Button
                        @click="handleClose"
                        :disabled="isLoading"
                        :class="isDarkMode ? 'bg-slate-700 hover:bg-slate-600 text-gray-100 border-slate-600' : 'bg-gray-100 hover:bg-gray-200 text-gray-700 border-gray-300'"
                        class="flex-1 font-semibold py-2 rounded-lg border transition-all duration-300 disabled:opacity-60 text-xs"
                    >
                        {{ claimSuccess || hasReceivedToday ? 'Close' : 'Skip' }}
                    </Button>
                </div>

                <!-- Streak Reminder -->
                <div :class="isDarkMode ? 'text-gray-500' : 'text-gray-500'" class="text-center text-xs mt-1.5">
                    <p>🔥 Log in daily for rewards</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
