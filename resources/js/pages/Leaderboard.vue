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

interface RankTier {
    id: number;
    name: string;
    icon: string;
    color: string;
    minRank: number;
    maxRank: number | null;
}

interface LeaderboardEntry {
    rank: number;
    userId: number;
    name: string;
    xp: number;
    level: number;
    streakDays: number;
    achievements: number;
    isCurrentUser?: boolean;
    rankTier: RankTier;
}

interface Props {
    leaderboard: LeaderboardEntry[];
    leaderboardTotal: number;
    userRank: LeaderboardEntry;
    allTiers: RankTier[];
    stats: {
        totalUsers: number;
        topXP: number;
        myRank: number;
        xpToNextRank: number;
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
        title: 'Leaderboard',
        href: '#',
    },
];

const selectedTier = ref<string | null>(null);
const displayCount = ref(20);

// Get all tiers sorted from Supreme to Plastic (by minRank ascending)
const availableTiers = computed(() => {
    return props.allTiers.sort((a, b) => a.minRank - b.minRank);
});

const getRankBadge = (rank: number) => {
    const badges: Record<number, string> = {
        1: '🥇',
        2: '🥈',
        3: '🥉',
    };
    return badges[rank] || '📊';
};

const loadMore = () => {
    displayCount.value = Math.min(displayCount.value + 20, props.leaderboardTotal);
};

const getFilteredLeaderboard = computed(() => {
    let filtered = [...props.leaderboard];

    // Filter by selected tier
    if (selectedTier.value) {
        filtered = filtered.filter((entry) => entry.rankTier.name === selectedTier.value);
    }

    // Always sort by XP within the filtered tier
    return filtered.sort((a, b) => b.xp - a.xp);
});

const topThree = computed(() => {
    return getFilteredLeaderboard.value.slice(0, 3);
});

const restOfLeaderboard = computed(() => {
    return getFilteredLeaderboard.value.slice(3);
});
</script>

<template>

    <Head title="Leaderboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Your Rank Card / Skeleton -->
            <SkeletonStats v-if="isLoading" :count="1" />
            <Card v-else
                class="border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-r from-accent/20 to-accent/5">
                <CardContent class="pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Your Rank</p>
                            <div class="flex items-center gap-3">
                                <h2 class="text-3xl font-bold">#{{ stats.myRank }}</h2>
                                <span class="text-3xl">{{ userRank.rankTier.icon }}</span>
                            </div>
                            <p class="text-sm font-semibold mt-1" :style="{ color: userRank.rankTier.color }">
                                {{ userRank.rankTier.name }}
                            </p>
                            <p class="text-xs text-muted-foreground mt-1">{{ stats.totalUsers }} Total Players</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-muted-foreground mb-2">
                                {{ userRank.xp.toLocaleString() }} XP
                            </p>
                            <p class="text-xs text-muted-foreground">
                                {{ stats.xpToNextRank }} XP to next rank
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Tier Filter Tabs -->
            <div class="flex gap-2 border-b border-sidebar-border/30 overflow-x-auto pb-2">
                <button @click="selectedTier = null" :class="[
                    'px-4 py-2 text-sm font-medium transition-colors whitespace-nowrap',
                    !selectedTier
                        ? 'text-accent border-b-2 border-accent'
                        : 'text-muted-foreground hover:text-foreground'
                ]">
                    All Tiers
                </button>
                <button v-for="tier in availableTiers" :key="tier.name" @click="selectedTier = tier.name" :class="[
                    'px-4 py-2 text-sm font-medium transition-colors whitespace-nowrap',
                    selectedTier === tier.name
                        ? 'border-b-2'
                        : 'text-muted-foreground hover:text-foreground'
                ]" :style="{
                        color: selectedTier === tier.name ? tier.color : undefined,
                        borderColor: selectedTier === tier.name ? tier.color : undefined,
                    }">
                    {{ tier.icon }} {{ tier.name }}
                </button>
            </div>

            <!-- Top 3 Podium / Skeleton -->
            <SkeletonCard v-if="isLoading" :count="3" />
            <div v-else-if="topThree.length >= 1" class="grid gap-4 md:grid-cols-3 mb-6">
                <!-- 2nd Place -->
                <div v-if="topThree[1]" class="order-1 md:order-1">
                    <Card
                        class="border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow">
                        <CardContent class="pt-6 text-center">
                            <div class="text-4xl mb-2">{{ topThree[1].rankTier.icon }}</div>
                            <h3 class="font-semibold text-lg mb-1">{{ topThree[1].name }}</h3>
                            <p class="text-xs font-semibold mb-2" :style="{ color: topThree[1].rankTier.color }">
                                {{ topThree[1].rankTier.name }}
                            </p>
                            <p class="text-muted-foreground text-sm mb-3">
                                {{ topThree[1].xp.toLocaleString() }} XP
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded bg-accent/10">
                                    <p class="text-muted-foreground">Level</p>
                                    <p class="font-bold">{{ topThree[1].level }}</p>
                                </div>
                                <div class="p-2 rounded bg-accent/10">
                                    <p class="text-muted-foreground">Streak</p>
                                    <p class="font-bold">{{ topThree[1].streakDays }}d</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- 1st Place -->
                <div v-if="topThree[0]" class="order-0 md:order-2">
                    <Card
                        :class="[
                            'border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow',
                            topThree[0].rank === 1 && topThree[0].rankTier.name === 'SUPREME'
                                ? 'border-yellow-500/50 animate-pulse shadow-lg shadow-yellow-500/50 bg-gradient-to-br from-yellow-500/20 via-yellow-500/10 to-transparent'
                                : 'border-sidebar-border/70'
                        ]">
                        <CardContent class="pt-6 text-center relative">
                            <div v-if="topThree[0].rank === 1 && topThree[0].rankTier.name === 'SUPREME'" class="absolute top-4 right-4 inline-block px-3 py-1 rounded-full bg-yellow-500/30 border border-yellow-500/50">
                                <span class="text-lg font-bold text-yellow-500">#{{ topThree[0].rank }}</span>
                            </div>
                            <div v-else class="absolute top-4 right-4 inline-block px-3 py-1 rounded-full" :style="{ backgroundColor: topThree[0].rankTier.color + '20', borderColor: topThree[0].rankTier.color + '50' }" style="border-width: 1px;">
                                <span class="text-lg font-bold" :style="{ color: topThree[0].rankTier.color }">#{{ topThree[0].rank }}</span>
                            </div>
                            <div class="text-6xl mb-3">{{ topThree[0].rankTier.icon }}</div>
                            <h3 class="font-bold text-2xl mb-2 bg-gradient-to-r from-yellow-400 to-yellow-600 bg-clip-text text-transparent">{{ topThree[0].name }}</h3>
                            <p class="text-sm font-bold mb-3 px-3 py-1 inline-block rounded-md" :style="{ color: topThree[0].rankTier.color, backgroundColor: topThree[0].rankTier.color + '20' }">
                                {{ topThree[0].rankTier.name }}
                            </p>
                            <p class="font-semibold text-sm mb-3">
                                {{ topThree[0].xp.toLocaleString() }} XP
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded"
                                    :style="{ backgroundColor: topThree[0].rankTier.color + '10' }">
                                    <p class="text-muted-foreground">Level</p>
                                    <p class="font-bold">{{ topThree[0].level }}</p>
                                </div>
                                <div class="p-2 rounded"
                                    :style="{ backgroundColor: topThree[0].rankTier.color + '10' }">
                                    <p class="text-muted-foreground">Streak</p>
                                    <p class="font-bold">{{ topThree[0].streakDays }}d</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- 3rd Place -->
                <div v-if="topThree[2]" class="order-2 md:order-3">
                    <Card
                        class="border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow">
                        <CardContent class="pt-6 text-center">
                            <div class="text-4xl mb-2">{{ topThree[2].rankTier.icon }}</div>
                            <h3 class="font-semibold text-lg mb-1">{{ topThree[2].name }}</h3>
                            <p class="text-xs font-semibold mb-2" :style="{ color: topThree[2].rankTier.color }">
                                {{ topThree[2].rankTier.name }}
                            </p>
                            <p class="text-muted-foreground text-sm mb-3">
                                {{ topThree[2].xp.toLocaleString() }} XP
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded"
                                    :style="{ backgroundColor: topThree[2].rankTier.color + '10' }">
                                    <p class="text-muted-foreground">Level</p>
                                    <p class="font-bold">{{ topThree[2].level }}</p>
                                </div>
                                <div class="p-2 rounded"
                                    :style="{ backgroundColor: topThree[2].rankTier.color + '10' }">
                                    <p class="text-muted-foreground">Streak</p>
                                    <p class="font-bold">{{ topThree[2].streakDays }}d</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Full Leaderboard / Skeleton -->
            <SkeletonCard v-if="isLoading" :count="10" />
            <Card v-else class="border-sidebar-border/70 dark:border-sidebar-border">
                <CardHeader>
                    <CardTitle>Rankings</CardTitle>
                    <CardDescription>{{ selectedTier ? selectedTier + ' Tier' : 'All Tiers' }} • Showing {{ displayCount
                        }} of {{ leaderboardTotal
                        }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2">
                        <div v-for="(entry, index) in restOfLeaderboard.slice(0, displayCount - 3)" :key="entry.userId"
                            :class="[
                                'flex items-center gap-4 p-3 rounded-lg transition-all duration-150',
                                entry.isCurrentUser
                                    ? 'bg-accent/20 border border-accent/50'
                                    : 'hover:bg-accent/10 border border-transparent'
                            ]">
                            <div class="flex items-center gap-2 w-16 flex-shrink-0">
                                <span class="text-lg">{{ entry.rankTier.icon }}</span>
                                <span class="font-bold text-sm">{{ entry.rank }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p :class="['font-medium text-sm', entry.isCurrentUser && 'font-bold']">
                                        {{ entry.name }}
                                    </p>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded" :style="{
                                        color: entry.rankTier.color,
                                        backgroundColor: entry.rankTier.color + '20'
                                    }">
                                        {{ entry.rankTier.name }}
                                    </span>
                                </div>
                                <p class="text-xs text-muted-foreground">
                                    Level {{ entry.level }} • {{ entry.streakDays }} day streak
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-semibold text-sm">
                                    {{ entry.xp.toLocaleString() }}
                                </p>
                                <p class="text-xs text-muted-foreground capitalize">
                                    XP
                                </p>
                            </div>
                            <Button size="sm" variant="ghost">View</Button>
                        </div>
                    </div>

                    <!-- Load More Button -->
                    <div v-if="displayCount < leaderboardTotal"
                        class="flex justify-center pt-4 border-t border-sidebar-border/30">
                        <Button @click="loadMore" variant="outline" class="mt-2">
                            Load More
                        </Button>
                    </div>
                    <div v-else class="flex justify-center pt-4 border-t border-sidebar-border/30">
                        <p class="text-xs text-muted-foreground mt-2">All rankings loaded</p>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
