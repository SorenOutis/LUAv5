<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import Progress from '@/components/ui/progress/Progress.vue';
import EnrollmentConfirmDialog from '@/components/enrollments/EnrollmentConfirmDialog.vue';
import SkeletonStats from '@/components/SkeletonStats.vue';
import SkeletonCard from '@/components/SkeletonCard.vue';

interface Course {
    id: number;
    name: string;
    description: string;
    instructor: string;
    progress: number;
    completedLessons: number;
    totalLessons: number;
    xpEarned: number;
    nextDeadline: string;
    category: string;
    difficulty: 'Beginner' | 'Intermediate' | 'Advanced';
    thumbnail?: string;
}

interface Props {
    enrolledCourses: Course[];
    pendingCourses: Course[];
    completedCourses: Course[];
    availableCourses: Course[];
    stats: {
        totalEnrolled: number;
        totalCompleted: number;
        averageProgress: number;
        totalXPEarned: number;
    };
}

const props = defineProps<Props>();
const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Courses',
        href: '#',
    },
];

const selectedFilter = ref<'enrolled' | 'pending' | 'completed' | 'available'>('available');
const enrollmentDialogOpen = ref(false);
const selectedCourseForEnrollment = ref<{ id: number; name: string } | null>(null);
const isEnrolling = ref(false);
const isLoading = computed(() => page.props.loading === true);

const getDifficultyColor = (difficulty: string) => {
    const colors: Record<string, string> = {
        'Beginner': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'Intermediate': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'Advanced': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    };
    return colors[difficulty] || 'bg-gray-100 text-gray-800';
};

const getFilteredCourses = () => {
    switch (selectedFilter.value) {
        case 'pending':
            return props.pendingCourses;
        case 'completed':
            return props.completedCourses;
        case 'available':
            return props.availableCourses;
        default:
            return props.enrolledCourses;
    }
};

const openEnrollmentDialog = (courseId: number, courseName: string) => {
    selectedCourseForEnrollment.value = { id: courseId, name: courseName };
    enrollmentDialogOpen.value = true;
};

const confirmEnrollment = () => {
    if (!selectedCourseForEnrollment.value) return;
    
    isEnrolling.value = true;
    router.post(`/courses/${selectedCourseForEnrollment.value.id}/enroll`, {}, {
        onFinish: () => {
            isEnrolling.value = false;
            enrollmentDialogOpen.value = false;
            selectedCourseForEnrollment.value = null;
            selectedFilter.value = 'enrolled';
        }
    });
};
</script>

<template>
    <Head title="Courses" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Stats Section -->
            <SkeletonStats v-if="isLoading" :count="4" />
            <div v-else class="grid gap-4 md:grid-cols-4">
                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Enrolled</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalEnrolled }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Active courses</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Completed</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalCompleted }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Finished</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">Avg Progress</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.averageProgress }}%</div>
                        <p class="text-xs text-muted-foreground mt-1">Overall</p>
                    </CardContent>
                </Card>

                <Card class="border-sidebar-border/70 dark:border-sidebar-border">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-medium">XP Earned</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalXPEarned.toLocaleString() }}</div>
                        <p class="text-xs text-muted-foreground mt-1">Total</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Filter Tabs -->
            <div class="flex gap-2 border-b border-sidebar-border/30">
                <button
                    v-for="filter in ['available', 'enrolled', 'pending', 'completed'] as const"
                    :key="filter"
                    @click="selectedFilter = filter"
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors capitalize',
                        selectedFilter === filter
                            ? 'text-accent border-b-2 border-accent'
                            : 'text-muted-foreground hover:text-foreground'
                    ]"
                >
                    {{ filter }} ({{ 
                        filter === 'enrolled' ? enrolledCourses.length 
                        : filter === 'pending' ? pendingCourses.length 
                        : filter === 'completed' ? completedCourses.length 
                        : availableCourses.length 
                    }})
                </button>
            </div>

            <!-- Courses Grid -->
            <SkeletonCard v-if="isLoading" :count="6" />
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="course in getFilteredCourses()" :key="course.id">
                    <Card class="border-sidebar-border/70 dark:border-sidebar-border h-full hover:shadow-md transition-shadow">
                        <CardHeader>
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <CardTitle class="text-lg">{{ course.name }}</CardTitle>
                                    <CardDescription>by {{ course.instructor }}</CardDescription>
                                </div>
                                <span :class="['px-2 py-1 text-xs rounded-full font-medium', getDifficultyColor(course.difficulty)]">
                                    {{ course.difficulty }}
                                </span>
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <p class="text-sm text-muted-foreground">{{ course.description }}</p>

                            <div v-if="selectedFilter !== 'available'" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">Progress</span>
                                    <span class="text-sm text-muted-foreground">{{ course.progress }}%</span>
                                </div>
                                <Progress :value="course.progress" class="h-2" />
                                <p class="text-xs text-muted-foreground">
                                    {{ course.completedLessons }} / {{ course.totalLessons }} lessons
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div class="p-2 rounded bg-accent/10 border border-accent/20">
                                    <p class="text-xs text-muted-foreground">Category</p>
                                    <p class="font-medium">{{ course.category }}</p>
                                </div>
                                <div class="p-2 rounded bg-yellow-500/10 border border-yellow-500/20">
                                    <p class="text-xs text-muted-foreground">XP Earned</p>
                                    <p class="font-medium text-yellow-600 dark:text-yellow-400">+{{ course.xpEarned }}</p>
                                </div>
                            </div>

                            <div v-if="selectedFilter !== 'available'" class="text-xs text-muted-foreground">
                                Next deadline: {{ course.nextDeadline }}
                            </div>

                            <Button 
                                class="w-full" 
                                :variant="selectedFilter === 'available' ? 'default' : 'outline'"
                                @click="selectedFilter === 'available' ? openEnrollmentDialog(course.id, course.name) : null"
                            >
                                {{ selectedFilter === 'available' ? 'Enroll Now' : 'Continue' }}
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="getFilteredCourses().length === 0" class="text-center py-12">
                <p class="text-muted-foreground mb-4">No {{ selectedFilter }} courses yet</p>
                <Button v-if="selectedFilter !== 'available'" variant="outline">
                    Browse Available Courses
                </Button>
            </div>
        </div>

        <!-- Enrollment Confirmation Dialog -->
        <EnrollmentConfirmDialog
            :open="enrollmentDialogOpen"
            :courseTitle="selectedCourseForEnrollment?.name || ''"
            :courseId="selectedCourseForEnrollment?.id || 0"
            :isLoading="isEnrolling"
            @close="enrollmentDialogOpen = false"
            @confirm="confirmEnrollment"
        />
    </AppLayout>
</template>
