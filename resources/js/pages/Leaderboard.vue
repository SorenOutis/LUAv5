<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';

interface LeaderboardEntry {
    rank: number;
    userId: number;
    name: string;
    xp: number;
    level: number;
    streakDays: number;
    achievements: number;
    isCurrentUser?: boolean;
}

interface Props {
    leaderboard: LeaderboardEntry[];
    userRank: LeaderboardEntry;
    stats: {
        totalUsers: number;
        topXP: number;
        myRank: number;
        xpToNextRank: number;
    };
}

const props = defineProps<Props>();

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

const selectedFilter = ref<'xp' | 'level' | 'streak' | 'achievements'>('xp');

const getRankBadge = (rank: number) => {
    const badges: Record<number, string> = {
        1: '🥇',
        2: '🥈',
        3: '🥉',
    };
    return badges[rank] || '📊';
};

const getFilteredLeaderboard = computed(() => {
    if (selectedFilter.value === 'xp') {
        return [...props.leaderboard].sort((a, b) => b.xp - a.xp);
    } else if (selectedFilter.value === 'level') {
        return [...props.leaderboard].sort((a, b) => b.level - a.level);
    } else if (selectedFilter.value === 'streak') {
        return [...props.leaderboard].sort((a, b) => b.streakDays - a.streakDays);
    } else {
        return [...props.leaderboard].sort((a, b) => b.achievements - a.achievements);
    }
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
            <!-- Your Rank Card -->
            <Card class="border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-r from-accent/20 to-accent/5">
                <CardContent class="pt-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-muted-foreground mb-1">Your Rank</p>
                            <h2 class="text-3xl font-bold">#{{ stats.myRank }}</h2>
                            <p class="text-sm text-muted-foreground mt-1">{{ stats.totalUsers }} Total Players</p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl mb-2">
                                {{ getRankBadge(stats.myRank) }}
                            </div>
                            <p class="text-xs text-muted-foreground">
                                {{ stats.xpToNextRank }} XP to next rank
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Filter Tabs -->
            <div class="flex gap-2 border-b border-sidebar-border/30">
                <button
                    v-for="filter in ['xp', 'level', 'streak', 'achievements'] as const"
                    :key="filter"
                    @click="selectedFilter = filter"
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors capitalize',
                        selectedFilter === filter
                            ? 'text-accent border-b-2 border-accent'
                            : 'text-muted-foreground hover:text-foreground'
                    ]"
                >
                    {{ filter }}
                </button>
            </div>

            <!-- Top 3 Podium -->
            <div v-if="topThree.length >= 1" class="grid gap-4 md:grid-cols-3 mb-6">
                <!-- 2nd Place -->
                <div v-if="topThree[1]" class="order-1 md:order-1">
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow">
                        <CardContent class="pt-6 text-center">
                            <div class="text-4xl mb-2">🥈</div>
                            <h3 class="font-semibold text-lg mb-1">{{ topThree[1].name }}</h3>
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
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow border-yellow-500/50">
                        <CardContent class="pt-6 text-center">
                            <div class="text-5xl mb-2">🏆</div>
                            <h3 class="font-semibold text-lg mb-1">{{ topThree[0].name }}</h3>
                            <p class="text-muted-foreground text-sm mb-3">
                                {{ topThree[0].xp.toLocaleString() }} XP
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded bg-yellow-500/10">
                                    <p class="text-muted-foreground">Level</p>
                                    <p class="font-bold">{{ topThree[0].level }}</p>
                                </div>
                                <div class="p-2 rounded bg-yellow-500/10">
                                    <p class="text-muted-foreground">Streak</p>
                                    <p class="font-bold">{{ topThree[0].streakDays }}d</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- 3rd Place -->
                <div v-if="topThree[2]" class="order-2 md:order-3">
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow">
                        <CardContent class="pt-6 text-center">
                            <div class="text-4xl mb-2">🥉</div>
                            <h3 class="font-semibold text-lg mb-1">{{ topThree[2].name }}</h3>
                            <p class="text-muted-foreground text-sm mb-3">
                                {{ topThree[2].xp.toLocaleString() }} XP
                            </p>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="p-2 rounded bg-orange-500/10">
                                    <p class="text-muted-foreground">Level</p>
                                    <p class="font-bold">{{ topThree[2].level }}</p>
                                </div>
                                <div class="p-2 rounded bg-orange-500/10">
                                    <p class="text-muted-foreground">Streak</p>
                                    <p class="font-bold">{{ topThree[2].streakDays }}d</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Full Leaderboard -->
            <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                <CardHeader>
                    <CardTitle>Rankings</CardTitle>
                    <CardDescription>Sorted by {{ selectedFilter }}</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-2">
                        <div v-for="(entry, index) in restOfLeaderboard" :key="entry.userId"
                            :class="[
                                'flex items-center gap-4 p-3 rounded-lg transition-all duration-150',
                                entry.isCurrentUser
                                    ? 'bg-accent/20 border border-accent/50'
                                    : 'hover:bg-accent/10 border border-transparent'
                            ]">
                            <div class="font-bold text-lg w-8 text-center">
                                {{ getRankBadge(index + 4) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p :class="['font-medium text-sm', entry.isCurrentUser && 'font-bold']">
                                    {{ entry.name }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    Level {{ entry.level }} • {{ entry.streakDays }} day streak
                                </p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="font-semibold text-sm">
                                    {{ entry.xp.toLocaleString() }}
                                </p>
                                <p class="text-xs text-muted-foreground capitalize">
                                    {{ selectedFilter }}
                                </p>
                            </div>
                            <Button size="sm" variant="ghost">View</Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
