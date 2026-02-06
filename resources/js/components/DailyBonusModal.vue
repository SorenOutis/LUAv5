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
}>();

const { xp: addXPToast } = useToast();

const isLoading = ref(false);
const claimError = ref('');
const claimSuccess = ref(false);
const bonusData = ref<any>(null);
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
        claimError.value = '';
        claimSuccess.value = false;
        bonusData.value = null;
        isOpen.value = false;
    }
};
</script>

<template>
    <Dialog :open="isOpen" @update:open="handleClose">
        <DialogContent class="max-w-sm border-0 shadow-2xl overflow-hidden md:max-w-sm max-w-xs">
            <!-- Background gradient - theme aware -->
            <div :class="isDarkMode ? 'bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900' : 'bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50'" class="absolute inset-0"></div>
            
            <!-- Decorative circles - theme aware (smaller on mobile) -->
            <div :class="isDarkMode ? 'from-amber-900/30 to-orange-900/30' : 'from-amber-200 to-orange-200'" class="absolute -top-16 -right-16 md:-top-20 md:-right-20 w-28 md:w-40 h-28 md:h-40 bg-gradient-to-br rounded-full blur-3xl opacity-30"></div>
            <div :class="isDarkMode ? 'from-yellow-900/30 to-amber-900/30' : 'from-yellow-200 to-amber-200'" class="absolute -bottom-16 -left-16 md:-bottom-20 md:-left-20 w-28 md:w-40 h-28 md:h-40 bg-gradient-to-tr rounded-full blur-3xl opacity-30"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-4 md:gap-6 py-6 md:py-8 px-4 md:px-6">
                <!-- Success State -->
                <transition
                    enter-active-class="animate-in fade-in scale-in duration-300"
                    leave-active-class="animate-out fade-out scale-out duration-200"
                >
                    <div v-if="claimSuccess" class="flex flex-col items-center gap-3 md:gap-4 w-full">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full blur-2xl opacity-40"></div>
                            <div class="relative bg-gradient-to-br from-green-50 to-emerald-50 rounded-full p-3 md:p-4 border border-green-200">
                                <CheckCircle2 class="w-8 md:w-12 h-8 md:h-12 text-green-600" stroke-width="1.5" />
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg md:text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent mb-1 md:mb-2">
                                Bonus Claimed!
                            </h3>
                            <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" class="text-xs md:text-sm">
                                You earned <span class="font-semibold text-amber-500">{{ xpAmount }} XP</span>
                            </p>
                            <p :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'" class="text-xs mt-1 md:mt-2">
                                Total XP: <span :class="isDarkMode ? 'text-gray-200' : 'text-gray-700'" class="font-semibold">{{ bonusData?.total_xp }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Default State -->
                     <div v-else-if="!hasReceivedToday" class="flex flex-col items-center gap-3 md:gap-4 w-full">
                         <!-- Icon -->
                         <div class="relative">
                             <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full blur-2xl opacity-40 animate-pulse"></div>
                             <div class="relative bg-gradient-to-br from-amber-100 to-orange-100 rounded-full p-3 md:p-5 border-2 border-amber-300/50">
                                 <span class="text-3xl md:text-5xl drop-shadow-lg">🎁</span>
                             </div>
                         </div>

                         <!-- Title -->
                          <div class="text-center space-y-1 md:space-y-2">
                              <h2 class="text-xl md:text-3xl font-bold bg-gradient-to-r from-amber-600 via-orange-500 to-yellow-600 bg-clip-text text-transparent">
                                  Daily Login Bonus
                              </h2>
                              <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" class="text-xs md:text-sm">
                                  Claim your daily reward and keep your streak going!
                              </p>
                          </div>

                         <!-- XP Display -->
                          <div class="relative mt-1 md:mt-2 w-full">
                              <div class="absolute inset-0 bg-gradient-to-r from-amber-300 to-orange-300 rounded-2xl blur-lg opacity-30"></div>
                              <div :class="isDarkMode ? 'bg-gradient-to-br from-slate-800 to-slate-700 border-slate-600/60' : 'bg-gradient-to-br from-white to-amber-50 border-amber-200/60'" class="relative rounded-2xl px-4 md:px-8 py-4 md:py-6 border shadow-lg">
                                  <div class="flex items-center justify-center gap-1 md:gap-2">
                                      <span class="text-3xl md:text-5xl font-bold bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">
                                          +{{ xpAmount }}
                                      </span>
                                      <span :class="isDarkMode ? 'text-gray-200' : 'text-gray-700'" class="text-lg md:text-2xl font-semibold">XP</span>
                                  </div>
                              </div>
                          </div>
                     </div>

                    <!-- Already Claimed State -->
                     <div v-else class="flex flex-col items-center gap-3 md:gap-4 w-full">
                         <div class="relative">
                             <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full blur-2xl opacity-30"></div>
                             <div class="relative bg-gradient-to-br from-blue-50 to-cyan-50 rounded-full p-3 md:p-4 border border-blue-200">
                                 <span class="text-3xl md:text-4xl">⏰</span>
                             </div>
                         </div>
                         <div class="text-center">
                              <h3 class="text-lg md:text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent mb-1 md:mb-2">
                                  Already Claimed
                              </h3>
                              <p :class="isDarkMode ? 'text-gray-300' : 'text-gray-600'" class="text-xs md:text-sm">
                                  You've already claimed your bonus today.
                              </p>
                              <p :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'" class="text-xs mt-1 md:mt-2">
                                  Come back tomorrow for another bonus!
                              </p>
                          </div>
                     </div>
                </transition>

                <!-- Error Message -->
                <transition
                    enter-active-class="animate-in fade-in slide-in-from-top duration-300"
                    leave-active-class="animate-out fade-out slide-out-to-top duration-200"
                >
                    <div v-if="claimError" :class="isDarkMode ? 'bg-gradient-to-r from-red-900/30 to-rose-900/30 border-red-700/60' : 'bg-gradient-to-r from-red-50 to-rose-50 border-red-200/60'" class="w-full border rounded-lg md:rounded-xl p-3 md:p-4 shadow-sm">
                         <div class="flex items-start gap-2 md:gap-3">
                             <X class="w-4 md:w-5 h-4 md:h-5 text-red-600 flex-shrink-0 mt-0.5" />
                             <div>
                                 <p :class="isDarkMode ? 'text-red-400' : 'text-red-700'" class="font-semibold text-xs md:text-sm">{{ claimError }}</p>
                             </div>
                         </div>
                     </div>
                </transition>

                <!-- Buttons -->
                <div class="w-full flex gap-2 md:gap-3 mt-2">
                    <Button
                        v-if="!hasReceivedToday && !claimSuccess"
                        @click="handleClaimBonus"
                        :disabled="isLoading"
                        class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-2 md:py-2.5 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 disabled:opacity-75 text-sm md:text-base"
                    >
                        <span v-if="!isLoading" class="flex items-center justify-center gap-2">
                            <span>Claim Bonus</span>
                        </span>
                        <span v-else class="flex items-center justify-center gap-2">
                            <span class="inline-block animate-spin">⚡</span>
                            <span>Claiming...</span>
                        </span>
                    </Button>
                    <Button
                         @click="handleClose"
                         :disabled="isLoading"
                         :class="isDarkMode ? 'bg-slate-700 hover:bg-slate-600 text-gray-100 border-slate-600' : 'bg-white hover:bg-gray-50 text-gray-700 border-gray-200'"
                         class="flex-1 font-semibold py-2 md:py-2.5 rounded-lg border shadow-sm hover:shadow-md transition-all duration-300 disabled:opacity-75 text-sm md:text-base"
                     >
                         {{ claimSuccess || hasReceivedToday ? 'Close' : 'Maybe Later' }}
                     </Button>
                </div>

                <!-- Streak Reminder -->
                 <div :class="isDarkMode ? 'text-gray-400' : 'text-gray-500'" class="text-center text-xs mt-1 md:mt-2">
                     <p>💪 Keep your streak alive. Log in daily for rewards!</p>
                 </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
