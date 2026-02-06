<script setup lang="ts">
import { computed } from 'vue';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import Button from '@/components/ui/button/Button.vue';
import { Flame, Sparkles, Zap, Rocket } from 'lucide-vue-next';

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

const isOpen = computed({
    get: () => props.open,
    set: (value) => emit('update:open', value),
});

const isNewRecord = computed(() => props.longestStreak === props.currentStreak && props.currentStreak > 1);
</script>

<template>
    <Dialog :open="isOpen" @update:open="(v) => (isOpen = v)">
        <DialogContent class="max-w-xs border border-primary/30 shadow-2xl overflow-hidden md:max-w-xs w-80">
            <!-- Clean background - using theme variables -->
            <div class="absolute inset-0 bg-card"></div>

            <!-- Accent line -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-primary/20"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center gap-3 py-6 px-5">
                <!-- Fire Icon -->
                <div class="relative">
                    <div class="relative bg-primary/20 rounded-xl p-4">
                        <Flame class="w-8 h-8 text-primary fill-primary" />
                    </div>
                </div>

                <!-- Streak Title -->
                <div class="text-center space-y-1">
                    <h2 class="text-xl font-semibold flex items-center justify-center gap-2 text-foreground">
                        <Sparkles v-if="isNewRecord" class="w-5 h-5 text-primary" />
                        {{ isNewRecord ? 'New Record!' : 'Day Marked!' }}
                    </h2>
                    <p class="text-muted-foreground text-xs">
                        {{ isNewRecord ? 'You reached a new personal best!' : 'Keep it going!' }}
                    </p>
                </div>

                <!-- Streak Display -->
                <div class="w-full space-y-2">
                    <!-- Current Streak -->
                    <div class="rounded-lg px-4 py-3 border bg-primary/10 border-primary/20">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground text-xs font-medium">Current Streak</span>
                            <span class="text-2xl font-bold text-primary">{{ currentStreak }}</span>
                        </div>
                    </div>

                    <!-- Longest Streak -->
                    <div class="rounded-lg px-4 py-3 border bg-muted/50 border-muted/50">
                        <div class="flex items-center justify-between">
                            <span class="text-muted-foreground text-xs font-medium">Best Streak</span>
                            <span class="text-xl font-bold text-foreground">{{ longestStreak }}</span>
                        </div>
                    </div>
                </div>

                <!-- Motivational Message -->
                <div class="text-muted-foreground text-center text-xs mt-2 flex items-center justify-center gap-2">
                    <Flame v-if="currentStreak >= 7" class="w-4 h-4 text-primary" />
                    <Zap v-else-if="currentStreak >= 3" class="w-4 h-4 text-primary" />
                    <Rocket v-else class="w-4 h-4 text-primary" />
                    <p v-if="currentStreak >= 7">Incredible dedication! {{ currentStreak }} days strong!</p>
                    <p v-else-if="currentStreak >= 3">Great job! Keep the momentum!</p>
                    <p v-else>Off to a great start!</p>
                </div>

                <!-- Close Button -->
                <Button @click="isOpen = false"
                    class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 text-xs">
                    Continue
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
