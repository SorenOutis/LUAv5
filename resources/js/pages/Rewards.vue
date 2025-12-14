<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SkeletonStats from '@/components/SkeletonStats.vue';
import SkeletonCard from '@/components/SkeletonCard.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';

interface Reward {
    id: number;
    name: string;
    description: string;
    icon: string;
    type: 'badge' | 'certificate' | 'unlock' | 'bonus';
    earnedAt: string;
    xpValue: number;
}

interface Badge {
    id: number;
    name: string;
    description: string;
    icon: string;
    rarity: 'common' | 'uncommon' | 'rare' | 'legendary';
    isUnlocked: boolean;
    earnedAt: string | null;
    xpReward: number;
    category: string;
}

interface Props {
    rewards: Reward[];
    badges: Badge[];
    stats: {
        totalRewards: number;
        totalBadges: number;
        totalXPFromRewards: number;
        nextMilestone: string;
    };
}

const props = defineProps<Props>();
const page = usePage();
const isLoading = computed(() => page.props.loading === true);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Rewards',
        href: '#',
    },
];

const selectedFilter = ref<'all' | 'badges' | 'certificates'>('all');

const getRarityColor = (rarity: string) => {
    const colors: Record<string, string> = {
        'common': 'text-gray-500',
        'uncommon': 'text-green-500',
        'rare': 'text-blue-500',
        'legendary': 'text-purple-500',
    };
    return colors[rarity] || 'text-gray-500';
};

const getRarityBg = (rarity: string) => {
    const colors: Record<string, string> = {
        'common': 'bg-gray-100 dark:bg-gray-900',
        'uncommon': 'bg-green-100 dark:bg-green-900',
        'rare': 'bg-blue-100 dark:bg-blue-900',
        'legendary': 'bg-purple-100 dark:bg-purple-900',
    };
    return colors[rarity] || 'bg-gray-100';
};

const getTypeColor = (type: string) => {
    const colors: Record<string, string> = {
        'badge': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'certificate': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'unlock': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'bonus': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    };
    return colors[type] || 'bg-gray-100';
};
</script>

<template>

    <Head title="Rewards" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Stats Section / Skeleton -->
            <SkeletonStats v-if="isLoading" :count="4" />
            <div v-else class="grid gap-4 md:grid-cols-4">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Total Rewards</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalRewards }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Earned</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Badges</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalBadges }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Collected</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Reward XP</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalXPFromRewards }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Total value</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Next Milestone</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-sm font-bold truncate">{{ stats.nextMilestone }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Coming soon</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Badges Section -->
            <div>
                <div class="mb-4">
                    <h2 class="text-lg font-semibold mb-2">Badges</h2>
                    <CardDescription>Special recognition for your achievements. Gray badges are locked until earned.
                    </CardDescription>
                </div>
                <SkeletonCard v-if="isLoading" :count="10" />
                <div v-else class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
                    <div v-for="badge in badges" :key="badge.id">
                        <Card
                            :class="['border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow relative', badge.isUnlocked ? getRarityBg(badge.rarity) : 'bg-gray-200 dark:bg-gray-800 opacity-50']">
                            <CardContent class="pt-6 flex flex-col items-center justify-center text-center h-full">
                                <div :class="['text-5xl mb-2 transition-opacity', !badge.isUnlocked && 'opacity-40']">{{
                                    badge.icon }}</div>
                                <div v-if="!badge.isUnlocked" class="absolute top-2 right-2 text-lg">🔒</div>
                                <h3 class="font-semibold text-sm mb-1">{{ badge.name }}</h3>
                                <p class="text-xs text-muted-foreground mb-3">{{ badge.description }}</p>
                                <p
                                    :class="['text-xs font-bold capitalize', badge.isUnlocked ? getRarityColor(badge.rarity) : 'text-gray-500']">
                                    {{ badge.rarity }}
                                </p>
                                <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">{{ badge.xpReward }} XP</p>
                                <p
                                    :class="['text-xs mt-2', badge.isUnlocked ? 'text-muted-foreground' : 'text-gray-500']">
                                    {{ badge.isUnlocked ? `Earned ${badge.earnedAt}` : 'Locked' }}
                                </p>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>

            <!-- Recent Rewards / Skeleton -->
            <SkeletonCard v-if="isLoading" :count="10" />
            <Card v-else class="border-sidebar-border/70 dark:border-sidebar-border">
                <CardHeader>
                    <CardTitle>Recent Rewards</CardTitle>
                    <CardDescription>Your recently earned rewards and unlocks</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-3">
                        <div v-for="reward in rewards" :key="reward.id"
                            class="flex items-center gap-4 p-3 rounded-lg hover:bg-accent/10 transition-colors">
                            <div class="text-3xl flex-shrink-0">{{ reward.icon }}</div>

                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm">{{ reward.name }}</h4>
                                <p class="text-sm text-muted-foreground">{{ reward.description }}</p>
                                <p class="text-xs text-muted-foreground mt-1">{{ reward.earnedAt }}</p>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span
                                    :class="['px-2 py-1 text-xs rounded-full font-medium', getTypeColor(reward.type)]">
                                    {{ reward.type }}
                                </span>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-yellow-600 dark:text-yellow-400">
                                        +{{ reward.xpValue }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">XP</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Reward Milestones -->
            <Card
                class="border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-r from-accent/20 to-accent/5">
                <CardHeader>
                    <CardTitle>Reward Milestones</CardTitle>
                    <CardDescription>Your reward progression path</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-3 md:grid-cols-4">
                        <div
                            :class="['p-4 rounded-lg border', stats.totalBadges >= 1 ? 'border-accent/30 bg-accent/5' : 'border-sidebar-border/30 bg-muted/50']">
                            <p class="text-sm text-muted-foreground mb-1">First Achievement</p>
                            <p :class="['font-bold', stats.totalBadges >= 1 ? 'text-accent' : 'text-muted-foreground']">
                                {{ stats.totalBadges >= 1 ? '✓ Earned' : 'Locked' }}
                            </p>
                        </div>
                        <div
                            :class="['p-4 rounded-lg border', stats.totalBadges >= 5 ? 'border-accent/30 bg-accent/5' : 'border-sidebar-border/30 bg-muted/50']">
                            <p class="text-sm text-muted-foreground mb-1">5 Achievements</p>
                            <p :class="['font-bold', stats.totalBadges >= 5 ? 'text-accent' : 'text-muted-foreground']">
                                {{ stats.totalBadges >= 5 ? '✓ Earned' : `${5 - stats.totalBadges} more` }}
                            </p>
                        </div>
                        <div
                            :class="['p-4 rounded-lg border', stats.totalBadges >= 10 ? 'border-accent/30 bg-accent/5' : 'border-sidebar-border/30 bg-muted/50']">
                            <p class="text-sm text-muted-foreground mb-1">10 Achievements</p>
                            <p
                                :class="['font-bold', stats.totalBadges >= 10 ? 'text-accent' : 'text-muted-foreground']">
                                {{ stats.totalBadges >= 10 ? '✓ Earned' : `${Math.max(0, 10 - stats.totalBadges)} more`
                                }}
                            </p>
                        </div>
                        <div
                            :class="['p-4 rounded-lg border', stats.totalBadges >= 25 ? 'border-accent/30 bg-accent/5' : 'border-sidebar-border/30 bg-muted/50']">
                            <p class="text-sm text-muted-foreground mb-1">Master Collector</p>
                            <p
                                :class="['font-bold', stats.totalBadges >= 25 ? 'text-accent' : 'text-muted-foreground']">
                                {{ stats.totalBadges >= 25 ? '✓ Earned' : `${Math.max(0, 25 - stats.totalBadges)} more`
                                }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Share Rewards -->
            <!-- <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                <CardContent class="pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold mb-1">Proud of your achievements?</h3>
                            <p class="text-sm text-muted-foreground">Share your badges and rewards with friends</p>
                        </div>
                        <div class="flex gap-2">
                            <Button variant="outline" size="sm">Share</Button>
                            <Button size="sm">View Profile</Button>
                        </div>
                    </div>
                </CardContent>
            </Card> -->
        </div>
    </AppLayout>
</template>
