<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, BookOpen, Code2, Trophy, Star, TrendingUp, Gift, Users, FileText, Gamepad2 } from 'lucide-vue-next';
import { computed } from 'vue';

interface NavItem {
    title: string;
    href: string;
    icon: any;
}

const page = usePage();

const navItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Courses',
        href: '/courses',
        icon: BookOpen,
    },
    {
        title: 'Leaderboard',
        href: '/leaderboard',
        icon: Trophy,
    },
    {
        title: 'Achievements',
        href: '/achievements',
        icon: Star,
    },
    {
        title: 'Assignments',
        href: '/assignments',
        icon: FileText,
    },
];

const currentPath = computed(() => page.url);

const isActive = (href: string) => {
    return currentPath.value === href || currentPath.value.startsWith(href + '?');
};
</script>

<template>
    <!-- Mobile Bottom Navigation - Only visible on mobile -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 md:hidden bg-card border-t border-border">
        <div class="flex items-center justify-around h-14 px-1">
            <Link
                v-for="item in navItems"
                :key="item.href"
                :href="item.href"
                class="flex flex-col items-center justify-center w-full h-full gap-0 text-[10px] font-normal transition-colors duration-200 py-0.5"
                :class="[
                    isActive(item.href)
                        ? 'text-primary'
                        : 'text-muted-foreground hover:text-foreground'
                ]"
            >
                <component :is="item.icon" class="w-4 h-4" />
                <span class="text-[10px] line-clamp-1 leading-tight">{{ item.title }}</span>
            </Link>
        </div>
    </nav>
</template>
