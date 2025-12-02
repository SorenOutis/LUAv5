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

interface Message {
    id: number;
    from: string;
    fromAvatar: string;
    subject: string;
    preview: string;
    body: string;
    timestamp: string;
    isRead: boolean;
    type: 'notification' | 'message' | 'announcement' | 'system';
}

interface Props {
    messages: Message[];
    stats: {
        unreadCount: number;
        totalMessages: number;
        lastMessageTime: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Messages',
        href: '#',
    },
];

const selectedFilter = ref<'all' | 'unread' | 'notifications' | 'announcements'>('all');
const selectedMessage = ref<Message | null>(null);

const getTypeIcon = (type: string) => {
    const icons: Record<string, string> = {
        'notification': '🔔',
        'message': '💬',
        'announcement': '📢',
        'system': '⚙️',
    };
    return icons[type] || '📧';
};

const getTypeColor = (type: string) => {
    const colors: Record<string, string> = {
        'notification': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'message': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'announcement': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        'system': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
    };
    return colors[type] || 'bg-gray-100';
};

const getFilteredMessages = () => {
    let filtered = [...props.messages];
    
    if (selectedFilter.value === 'unread') {
        filtered = filtered.filter(m => !m.isRead);
    } else if (selectedFilter.value === 'notifications') {
        filtered = filtered.filter(m => m.type === 'notification');
    } else if (selectedFilter.value === 'announcements') {
        filtered = filtered.filter(m => m.type === 'announcement');
    }
    
    return filtered.sort((a, b) => new Date(b.timestamp).getTime() - new Date(a.timestamp).getTime());
};

const unreadCount = computed(() => {
    return props.messages.filter(m => !m.isRead).length;
});
</script>

<template>
    <Head title="Messages" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Stats Section -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Unread</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ stats.unreadCount }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Messages</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Total</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalMessages }}</div>
                        <p class="text-xs text-muted-foreground mt-1">All messages</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Last Message</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-sm font-bold truncate">{{ stats.lastMessageTime }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Just now</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Archive</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Button variant="outline" size="sm" class="w-full">View Archived</Button>
                    </CardContent>
                </Card>
            </div>

            <!-- Filter Tabs -->
            <div class="flex gap-2 border-b border-sidebar-border/30 overflow-x-auto">
                <button
                    v-for="filter in ['all', 'unread', 'notifications', 'announcements'] as const"
                    :key="filter"
                    @click="selectedFilter = filter"
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors capitalize whitespace-nowrap',
                        selectedFilter === filter
                            ? 'text-accent border-b-2 border-accent'
                            : 'text-muted-foreground hover:text-foreground'
                    ]"
                >
                    {{ filter }} ({{ filter === 'all' ? stats.totalMessages : filter === 'unread' ? stats.unreadCount : 0 }})
                </button>
            </div>

            <!-- Messages List -->
            <div class="space-y-2">
                <div v-for="message in getFilteredMessages()" :key="message.id"
                    :class="[
                        'p-4 rounded-lg border border-sidebar-border/30 cursor-pointer transition-all duration-150 hover:shadow-md',
                        message.isRead ? 'bg-background' : 'bg-accent/5 border-accent/30'
                    ]"
                    @click="selectedMessage = message">
                    <div class="flex items-start gap-4">
                        <!-- Avatar/Icon -->
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-accent/20 border border-accent/30 flex items-center justify-center text-lg">
                                {{ getTypeIcon(message.type) }}
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-1">
                                <div class="flex-1">
                                    <h4 :class="['font-semibold text-sm', !message.isRead && 'font-bold']">
                                        {{ message.from }}
                                    </h4>
                                    <p class="text-sm font-medium truncate">{{ message.subject }}</p>
                                </div>
                                <span :class="['px-2 py-1 text-xs rounded-full font-medium flex-shrink-0 ml-2', getTypeColor(message.type)]">
                                    {{ message.type }}
                                </span>
                            </div>
                            <p class="text-sm text-muted-foreground truncate">{{ message.preview }}</p>
                            <p class="text-xs text-muted-foreground mt-1">{{ message.timestamp }}</p>
                        </div>

                        <!-- Unread Indicator -->
                        <div v-if="!message.isRead" class="flex-shrink-0 h-2 w-2 rounded-full bg-accent mt-2"></div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="getFilteredMessages().length === 0" class="text-center py-12">
                <p class="text-muted-foreground mb-4">No {{ selectedFilter }} messages</p>
                <Button variant="outline">Refresh</Button>
            </div>

            <!-- Message Detail Modal -->
            <div v-if="selectedMessage" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border w-full max-w-2xl max-h-[90vh] overflow-auto">
                    <CardHeader class="sticky top-0 bg-background border-b border-sidebar-border/30">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-accent/20 border border-accent/30 flex items-center justify-center">
                                    {{ getTypeIcon(selectedMessage.type) }}
                                </div>
                                <div>
                                    <h2 class="font-semibold">{{ selectedMessage.from }}</h2>
                                    <p class="text-xs text-muted-foreground">{{ selectedMessage.timestamp }}</p>
                                </div>
                            </div>
                            <button @click="selectedMessage = null" class="text-muted-foreground hover:text-foreground">
                                ✕
                            </button>
                        </div>
                        <CardTitle>{{ selectedMessage.subject }}</CardTitle>
                    </CardHeader>

                    <CardContent class="pt-6 space-y-4">
                        <div class="prose dark:prose-invert prose-sm max-w-none">
                            {{ selectedMessage.body }}
                        </div>

                        <div class="flex gap-2 pt-4 border-t border-sidebar-border/30">
                            <Button>Reply</Button>
                            <Button variant="outline">Archive</Button>
                            <Button variant="outline">Delete</Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
