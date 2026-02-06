<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import Button from '@/components/ui/button/Button.vue';
import { Flame } from 'lucide-vue-next';

interface Props {
    open: boolean;
    currentStreak: number;
    longestStreak: number;
}

const props = withDefaults(defineProps<Props>(), {
    open: false,
    currentStreak: 0,
    longestStreak: 0,
});

const emit = defineEmits<{
    'update:open': [value: boolean];
}>();

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

const isNewRecord = computed(() => props.longestStreak === props.currentStreak && props.currentStreak > 1);
</script>

<template>
    <Dialog :open="isOpen" @update:open="(v) => (isOpen = v)">
        <DialogContent class="max-w-xs border-0 shadow-2xl overflow-hidden md:max-w-xs w-80">
            <!-- Clean background - theme aware -->
            <div :class="isDarkMode ? 'bg-gradient-to-br from-slate-900 to-slate-800' : 'bg-white'" class="absolute inset-0"></div>

            <!-- Accent line -->
            <div :class="isDarkMode ? 'bg-orange-500/20' : 'bg-orange-100'" class="absolute top-0 left-0 right-0 h-1"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-3 py-6 px-5">
                <!-- Fire Icon -->
                <div class="relative">
                    <div :class="isDarkMode ? 'bg-orange-500/10' : 'bg-orange-50'" class="relative rounded-xl p-4">
                        <Flame class="w-8 h-8 text-orange-600 fill-orange-600" />
                    </div>
                </div>

                <!-- Streak Title -->
                <div class="text-center space-y-1">
                    <h2 :class="isDarkMode ? 'text-white' : 'text-gray-900'" class="text-xl font-semibold">
                        {{ isNewRecord ? '🎉 New Record!' : 'Day Marked!' }}
                    </h2>
                    <p :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs">
                        {{ isNewRecord ? 'You reached a new personal best!' : 'Keep it going!' }}
                    </p>
                </div>

                <!-- Streak Display -->
                <div class="w-full space-y-2">
                    <!-- Current Streak -->
                    <div :class="isDarkMode ? 'bg-orange-500/10 border-orange-500/20' : 'bg-orange-50 border-orange-200'" class="rounded-lg px-4 py-3 border">
                        <div class="flex items-center justify-between">
                            <span :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs font-medium">Current Streak</span>
                            <span class="text-2xl font-bold text-orange-600">{{ currentStreak }}</span>
                        </div>
                    </div>

                    <!-- Longest Streak -->
                    <div :class="isDarkMode ? 'bg-gray-800/50 border-gray-700/50' : 'bg-gray-50 border-gray-200'" class="rounded-lg px-4 py-3 border">
                        <div class="flex items-center justify-between">
                            <span :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-xs font-medium">Best Streak</span>
                            <span :class="isDarkMode ? 'text-gray-300' : 'text-gray-700'" class="text-xl font-bold">{{ longestStreak }}</span>
                        </div>
                    </div>
                </div>

                <!-- Motivational Message -->
                <div :class="isDarkMode ? 'text-gray-400' : 'text-gray-600'" class="text-center text-xs mt-2">
                    <p v-if="currentStreak >= 7">🌟 Incredible dedication! {{ currentStreak }} days strong!</p>
                    <p v-else-if="currentStreak >= 3">💪 Great job! Keep the momentum!</p>
                    <p v-else>🚀 Off to a great start!</p>
                </div>

                <!-- Close Button -->
                <Button
                    @click="isOpen = false"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 text-xs"
                >
                    Continue
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
