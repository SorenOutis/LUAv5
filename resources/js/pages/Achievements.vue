<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface Achievement {
    id: number;
    name: string;
    description: string;
    icon: string;
    category: string;
    difficulty: 'Easy' | 'Medium' | 'Hard' | 'Legendary';
    unlocked: boolean;
    unlockedAt?: string;
    xpReward: number;
    rarity: number; // percentage of users who have it
}

interface Props {
    unlockedAchievements: Achievement[];
    lockedAchievements: Achievement[];
    stats: {
        totalUnlocked: number;
        totalAchievements: number;
        completionPercentage: number;
        xpEarned: number;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Achievements',
        href: '#',
    },
];

const selectedFilter = ref<'all' | 'unlocked' | 'locked'>('all');
const selectedAchievement = ref<Achievement | null>(null);

const getDifficultyColor = (difficulty: string) => {
    const colors: Record<string, string> = {
        'Easy': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'Medium': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'Hard': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
        'Legendary': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    };
    return colors[difficulty] || 'bg-gray-100 text-gray-800';
};

const getFilteredAchievements = () => {
    if (selectedFilter.value === 'unlocked') {
        return props.unlockedAchievements;
    } else if (selectedFilter.value === 'locked') {
        return props.lockedAchievements;
    }
    return [...props.unlockedAchievements, ...props.lockedAchievements];
};

const getRarityLabel = (rarity: number) => {
    if (rarity >= 80) return 'Very Common';
    if (rarity >= 50) return 'Common';
    if (rarity >= 20) return 'Uncommon';
    if (rarity >= 5) return 'Rare';
    return 'Very Rare';
};
</script>

<template>
    <Head title="Achievements" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Stats Section -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Unlocked</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalUnlocked }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Achievements</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Total</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalAchievements }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Available</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Completion</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.completionPercentage }}%</div>
                        <p class="text-xs text-muted-foreground mt-1">Progress</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">XP Earned</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.xpEarned }}</div>
                        <p class="text-xs text-muted-foreground mt-1">From achievements</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Filter Tabs -->
            <div class="flex gap-2 border-b border-sidebar-border/30">
                <button
                    v-for="filter in ['all', 'unlocked', 'locked'] as const"
                    :key="filter"
                    @click="selectedFilter = filter"
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors capitalize',
                        selectedFilter === filter
                            ? 'text-accent border-b-2 border-accent'
                            : 'text-muted-foreground hover:text-foreground'
                    ]"
                >
                    {{ filter }} ({{ filter === 'all' ? stats.totalAchievements : filter === 'unlocked' ? stats.totalUnlocked : stats.totalAchievements - stats.totalUnlocked }})
                </button>
            </div>

            <!-- Achievements Grid -->
            <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                <div v-for="achievement in getFilteredAchievements()" :key="achievement.id">
                    <Card :class="[
                        'border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-all duration-150 cursor-pointer',
                        achievement.unlocked ? 'hover:scale-105' : 'opacity-50 grayscale hover:opacity-75'
                    ]"
                    @click="selectedAchievement = achievement">
                        <CardContent class="pt-6 flex flex-col items-center justify-center text-center h-full">
                            <div class="text-5xl mb-2">{{ achievement.icon }}</div>
                            <h3 class="font-semibold text-sm mb-1">{{ achievement.name }}</h3>
                            <p class="text-xs text-muted-foreground mb-2 line-clamp-2">{{ achievement.description }}</p>
                            
                            <div v-if="achievement.unlocked" class="w-full mt-auto pt-2 border-t border-sidebar-border/30">
                                <p class="text-xs text-green-600 dark:text-green-400 font-medium">
                                    ✓ Unlocked
                                </p>
                                <p class="text-xs text-muted-foreground">{{ achievement.unlockedAt }}</p>
                            </div>
                            
                            <div v-else class="w-full mt-auto pt-2">
                                <span :class="['text-xs rounded-full px-2 py-1 inline-block', getDifficultyColor(achievement.difficulty)]">
                                    {{ achievement.difficulty }}
                                </span>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="getFilteredAchievements().length === 0" class="text-center py-12">
                <p class="text-muted-foreground">No {{ selectedFilter === 'all' ? '' : selectedFilter }} achievements yet</p>
            </div>

            <!-- Achievement Details Modal -->
            <Dialog v-if="selectedAchievement" :open="!!selectedAchievement" @update:open="(open) => !open && (selectedAchievement = null)">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle class="flex items-center gap-3">
                            <span class="text-4xl">{{ selectedAchievement.icon }}</span>
                            {{ selectedAchievement.name }}
                        </DialogTitle>
                        <DialogDescription>
                            {{ selectedAchievement.description }}
                        </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg bg-accent/10 border border-accent/20">
                                <p class="text-xs text-muted-foreground mb-1">Difficulty</p>
                                <p :class="['font-bold text-sm', getDifficultyColor(selectedAchievement.difficulty)]">
                                    {{ selectedAchievement.difficulty }}
                                </p>
                            </div>
                            <div class="p-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                <p class="text-xs text-muted-foreground mb-1">XP Reward</p>
                                <p class="font-bold text-sm text-yellow-600 dark:text-yellow-400">
                                    +{{ selectedAchievement.xpReward }}
                                </p>
                            </div>
                            <div class="p-4 rounded-lg bg-accent/10 border border-accent/20">
                                <p class="text-xs text-muted-foreground mb-1">Category</p>
                                <p class="font-bold text-sm">{{ selectedAchievement.category }}</p>
                            </div>
                            <div class="p-4 rounded-lg bg-accent/10 border border-accent/20">
                                <p class="text-xs text-muted-foreground mb-1">Rarity</p>
                                <p class="font-bold text-sm">{{ getRarityLabel(selectedAchievement.rarity) }}</p>
                                <p class="text-xs text-muted-foreground">{{ selectedAchievement.rarity }}% have it</p>
                            </div>
                        </div>

                        <div v-if="selectedAchievement.unlocked" class="p-4 rounded-lg bg-green-500/10 border border-green-500/20">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">
                                ✓ Unlocked {{ selectedAchievement.unlockedAt }}
                            </p>
                        </div>
                        <div v-else class="p-4 rounded-lg bg-muted border border-sidebar-border/30">
                            <p class="text-sm font-medium text-muted-foreground">
                                Keep working to unlock this achievement!
                            </p>
                        </div>

                        <Button class="w-full" :variant="selectedAchievement.unlocked ? 'default' : 'outline'">
                            {{ selectedAchievement.unlocked ? 'Share Achievement' : 'View Requirements' }}
                        </Button>
                    </div>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>
