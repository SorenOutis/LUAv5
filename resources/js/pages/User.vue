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
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface UserProfile {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    bio?: string;
    joinedDate: string;
}

interface UserStats {
    totalXP: number;
    level: number;
    coursesEnrolled: number;
    assignmentsCompleted: number;
    achievementsUnlocked: number;
    currentStreak: number;
}

interface UserProgress {
    courseId: number;
    courseName: string;
    progress: number;
    completedLessons: number;
    totalLessons: number;
}

interface UserAchievement {
    id: number;
    name: string;
    description: string;
    icon: string;
    unlockedAt: string;
}

interface Props {
    user: UserProfile;
    stats: UserStats;
    progress: UserProgress[];
    achievements: UserAchievement[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'User Profile',
        href: '#',
    },
];

const selectedAchievement = ref<UserAchievement | null>(null);
const isEditingProfile = ref(false);

const editedProfile = ref({
    name: props.user.name,
    email: props.user.email,
    bio: props.user.bio || '',
});

const handleEditClick = () => {
    isEditingProfile.value = true;
};

const handleSaveProfile = () => {
    isEditingProfile.value = false;
};

const handleAchievementClick = (achievement: UserAchievement) => {
    selectedAchievement.value = achievement;
};
</script>

<template>
    <Head title="User Profile" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- User Header Section -->
            <Card class="border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-r from-accent/20 to-accent/5">
                <CardContent class="pt-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-accent to-accent/50 flex items-center justify-center text-2xl font-bold text-white">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>

                            <!-- User Info -->
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold">{{ user.name }}</h1>
                                <p class="text-muted-foreground">{{ user.email }}</p>
                                <p class="text-xs text-muted-foreground mt-1">
                                    Joined {{ user.joinedDate }}
                                </p>
                            </div>
                        </div>

                        <Button @click="handleEditClick">Edit Profile</Button>
                    </div>

                    <!-- Bio Section -->
                    <div v-if="user.bio" class="mt-4 p-4 rounded-lg bg-accent/5 border border-accent/20">
                        <p class="text-sm text-muted-foreground">{{ user.bio }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Stats Grid -->
            <div class="grid gap-4 md:grid-cols-5">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Total XP</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalXP.toLocaleString() }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Lifetime points</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Level</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.level }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Current level</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Courses</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.coursesEnrolled }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Enrolled</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Assignments</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.assignmentsCompleted }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Completed</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Streak</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.currentStreak }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Days active</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-4 lg:grid-cols-3">
                <!-- Left Column: Courses & Progress -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Course Progress -->
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                        <CardHeader>
                            <CardTitle>Course Progress</CardTitle>
                            <CardDescription>Your learning journey across all enrolled courses</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-if="progress.length === 0" class="text-center py-8">
                                <p class="text-muted-foreground">No courses enrolled yet</p>
                            </div>
                            <div v-for="course in progress" :key="course.courseId" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-sm">{{ course.courseName }}</h4>
                                        <p class="text-xs text-muted-foreground">
                                            {{ course.completedLessons }} / {{ course.totalLessons }} lessons
                                        </p>
                                    </div>
                                    <span class="text-sm font-semibold">{{ course.progress }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-muted overflow-hidden">
                                    <div
                                        class="h-full bg-gradient-to-r from-accent to-accent/60 transition-all duration-500"
                                        :style="{ width: `${course.progress}%` }"
                                    />
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Overview Stats -->
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                        <CardHeader>
                            <CardTitle>Achievement Summary</CardTitle>
                            <CardDescription>{{ stats.achievementsUnlocked }} achievements unlocked</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-accent/5 border border-accent/20">
                                    <span class="font-medium text-sm">Total Achievements</span>
                                    <span class="text-lg font-bold">{{ stats.achievementsUnlocked }}</span>
                                </div>
                                <div class="flex items-center justify-between p-3 rounded-lg bg-accent/5 border border-accent/20">
                                    <span class="font-medium text-sm">Success Rate</span>
                                    <span class="text-lg font-bold">{{ Math.round((stats.achievementsUnlocked / 20) * 100) }}%</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column: Achievements -->
                <div class="space-y-4">
                    <!-- Achievements -->
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                        <CardHeader>
                            <CardTitle class="text-base">Recent Achievements</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="achievements.length === 0" class="text-center py-8">
                                <p class="text-muted-foreground text-sm">No achievements yet</p>
                            </div>
                            <div v-else class="space-y-3">
                                <div v-for="achievement in achievements.slice(0, 5)" :key="achievement.id"
                                    class="flex flex-col items-center justify-center p-3 rounded border border-accent/30 bg-accent/10 cursor-pointer transition-all duration-150 hover:scale-105 hover:shadow-sm"
                                    :title="achievement.description"
                                    @click="handleAchievementClick(achievement)">
                                    <div class="text-3xl mb-1">{{ achievement.icon }}</div>
                                    <p class="text-xs text-center font-medium text-muted-foreground">{{ achievement.name }}</p>
                                </div>
                            </div>

                            <Button v-if="achievements.length > 5" class="w-full mt-4" variant="outline">
                                View All Achievements
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Profile Stats -->
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-accent/30 to-accent/10">
                        <CardHeader>
                            <CardTitle class="text-sm">Member Info</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div>
                                <p class="text-xs text-muted-foreground mb-1">Email</p>
                                <p class="text-sm font-medium">{{ user.email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground mb-1">Member Since</p>
                                <p class="text-sm font-medium">{{ user.joinedDate }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-muted-foreground mb-1">Current Streak</p>
                                <p class="text-sm font-medium">{{ stats.currentStreak }} days</p>
                            </div>
                            <Button class="w-full mt-2" variant="outline" size="sm">
                                Settings
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Achievement Details Dialog -->
        <Dialog v-if="selectedAchievement" :open="!!selectedAchievement" @update:open="(open) => !open && (selectedAchievement = null)">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2">
                        <span class="text-4xl">{{ selectedAchievement.icon }}</span>
                        {{ selectedAchievement.name }}
                    </DialogTitle>
                    <DialogDescription>
                        {{ selectedAchievement.description }}
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4">
                    <div class="p-4 rounded-lg bg-accent/10 border border-accent/30">
                        <p class="text-sm font-medium text-accent">✓ Unlocked {{ selectedAchievement.unlockedAt }}</p>
                    </div>
                    <Button class="w-full">Share Achievement</Button>
                </div>
            </DialogContent>
        </Dialog>

        <!-- Edit Profile Dialog -->
        <Dialog :open="isEditingProfile" @update:open="isEditingProfile = $event">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Edit Profile</DialogTitle>
                    <DialogDescription>
                        Update your profile information
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm font-medium">Name</label>
                        <input
                            v-model="editedProfile.name"
                            type="text"
                            class="w-full px-3 py-2 border rounded-md border-input bg-background mt-1"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input
                            v-model="editedProfile.email"
                            type="email"
                            class="w-full px-3 py-2 border rounded-md border-input bg-background mt-1"
                        />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Bio</label>
                        <textarea
                            v-model="editedProfile.bio"
                            class="w-full px-3 py-2 border rounded-md border-input bg-background mt-1"
                            rows="4"
                        />
                    </div>
                    <div class="flex gap-2">
                        <Button @click="handleSaveProfile" class="flex-1">Save Changes</Button>
                        <Button @click="isEditingProfile = false" variant="outline" class="flex-1">Cancel</Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
