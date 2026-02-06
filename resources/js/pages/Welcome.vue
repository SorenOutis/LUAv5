<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import { useLogo } from '@/composables/useLogo';
import { useAppearance } from '@/composables/useAppearance';
import VerificationSlider from '@/components/VerificationSlider.vue';
import RotatingText from '@/components/RotatingText.vue';
import NavigationBar from '@/components/NavigationBar.vue';
import { Trophy, TrendingUp, Users, Zap, Gamepad2, BarChart3, Award, Moon, Sun } from 'lucide-vue-next';

const mouseX = ref(0);
const mouseY = ref(0);
const showModal = ref(false);
const showVerification = ref(false);
const showAuthChoice = ref(false);
const verificationRedirectTo = ref<'login' | 'register'>('login');
const { hasLogo, logo, getLightLogo, getDarkLogo } = useLogo();
const { appearance, updateAppearance } = useAppearance();

const isDarkMode = computed(() => {
    if (appearance.value === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    return appearance.value === 'dark';
});

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const toggleTheme = () => {
    if (isDarkMode.value) {
        updateAppearance('light');
    } else {
        updateAppearance('dark');
    }
};

const openVerification = (redirectTo: 'login' | 'register') => {
    verificationRedirectTo.value = redirectTo;
    showVerification.value = true;
};

const handleVerified = () => {
    showVerification.value = false;

    // Mark verification in session before showing auth choice
    router.post('verification/mark', {}, {
        onSuccess: () => {
            showAuthChoice.value = true;
        },
        onError: (errors) => {
            console.error('Verification failed:', errors);
            showVerification.value = true;
        }
    });
};

const redirectToAuth = (path: 'login' | 'register') => {
    showAuthChoice.value = false;
    if (path === 'login') {
        router.visit(login());
    } else {
        router.visit(register());
    }
};

onMounted(() => {
    const savedAppearance = localStorage.getItem('appearance');
    if (savedAppearance) {
        updateAppearance(savedAppearance as 'light' | 'dark' | 'system');
    }

    document.addEventListener('mousemove', (e) => {
        mouseX.value = e.clientX;
        mouseY.value = e.clientY;
    });
});
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="min-h-screen bg-background text-foreground transition-colors duration-500 overflow-hidden relative">
        <!-- Animated Background Gradient Orbs -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute w-80 h-80 rounded-full blur-3xl opacity-20 bg-primary top-20 -left-40 animate-blob" />
            <div class="absolute w-80 h-80 rounded-full blur-3xl opacity-20 bg-secondary bottom-20 -right-40 animate-blob"
                style="animation-delay: 2s" />
            <div class="absolute w-80 h-80 rounded-full blur-3xl opacity-20 bg-accent top-1/2 left-1/2 animate-blob"
                style="animation-delay: 4s" />
        </div>

        <!-- Gradient Light Effect Following Mouse -->
        <div class="fixed inset-0 -z-10 pointer-events-none opacity-50" :style="{
            background: `radial-gradient(600px at ${mouseX}px ${mouseY}px, rgb(var(--color-primary-rgb) / 0.1), transparent 80%)`,
        }" />

        <!-- Navigation Bar -->
        <NavigationBar @open-verification="openVerification" @toggle-theme="toggleTheme" />
        <!-- Theme Toggle Button (Upper Right - Desktop Only) -->
        <button @click="toggleTheme"
            class="hidden md:flex fixed top-8 right-8 z-40 p-2.5 rounded-lg bg-background/80 backdrop-blur-xl border border-border text-foreground hover:bg-accent transition-all duration-300"
            :aria-label="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'">
            <Moon v-if="isDarkMode" :size="20" />
            <Sun v-else :size="20" />
        </button>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-6 lg:px-8 py-32 lg:py-40 relative z-10">
            <!-- Hero Section - Full Width Centered -->
            <div class="mb-20">
                <div class="space-y-8 text-center">
                    <div class="space-y-4">
                        <div class="flex justify-center">
                            <span
                                class="animate-fade-in-heading px-3 py-1 rounded-full text-sm font-semibold bg-primary/10 text-primary border border-primary/30">
                                Welcome to the Future
                            </span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-bold text-balance mb-6 animate-fade-in-heading"
                            style="animation-delay: 0.1s">
                            <span class="text-foreground">Elevate your</span>
                            <br />
                            <span class="inline-flex items-center justify-center flex-wrap gap-2 mt-4 sm:mt-6 md:mt-8">
                                <span class="text-foreground">Business</span>
                                <RotatingText :texts="['Growth', 'Innovation', 'Efficiency', 'Success', 'Performance']"
                                    :interval="2000"
                                    mainClassName="px-2 sm:px-2 md:px-3 bg-white text-black overflow-hidden py-1 sm:py-1 md:py-2 justify-center rounded-lg shadow-lg inline-block"
                                    splitLevelClassName="overflow-hidden pb-1 sm:pb-1 md:pb-1 inline-block" />
                            </span>
                        </h1>
                    </div>


                    <p class="text-lg sm:text-xl text-muted-foreground leading-relaxed max-w-3xl mx-auto font-light tracking-wide animate-fade-in-heading"
                        style="animation-delay: 0.2s">
                        Unlock your potential with our gamified learning platform. Track your
                        progress, earn achievements, compete on leaderboards, and master new skills.
                    </p>

                    <!-- Feature Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 max-w-4xl mx-auto">
                        <div class="group p-5 rounded-xl bg-card border border-border hover:shadow-md transition-all duration-300 backdrop-blur-sm animate-fade-in-heading"
                            style="animation-delay: 0.3s">
                            <Trophy class="w-6 h-6 mb-2 text-primary group-hover:scale-110 transition-transform" />
                            <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">
                                Gamified
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                Earn XP & Streaks
                            </p>
                        </div>
                        <div class="group p-5 rounded-xl bg-card border border-border hover:shadow-md transition-all duration-300 backdrop-blur-sm animate-fade-in-heading"
                            style="animation-delay: 0.35s">
                            <TrendingUp class="w-6 h-6 mb-2 text-primary group-hover:scale-110 transition-transform" />
                            <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">
                                Track Progress
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                Real-time Stats
                            </p>
                        </div>
                        <div class="group p-5 rounded-xl bg-card border border-border hover:shadow-md transition-all duration-300 backdrop-blur-sm animate-fade-in-heading"
                            style="animation-delay: 0.4s">
                            <Users class="w-6 h-6 mb-2 text-primary group-hover:scale-110 transition-transform" />
                            <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">
                                Compete
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                Leaderboards
                            </p>
                        </div>
                        <div class="group p-5 rounded-xl bg-card border border-border hover:shadow-md transition-all duration-300 backdrop-blur-sm animate-fade-in-heading"
                            style="animation-delay: 0.45s">
                            <Zap class="w-6 h-6 mb-2 text-primary group-hover:scale-110 transition-transform" />
                            <h3 class="font-semibold text-foreground group-hover:text-primary transition-colors">
                                Lightning Fast
                            </h3>
                            <p class="text-sm text-muted-foreground">
                                Smooth & Snappy
                            </p>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 justify-center">
                        <button v-if="canRegister" @click="openVerification('register')"
                            class="px-8 py-3 rounded-xl font-semibold bg-primary text-primary-foreground hover:bg-primary/90 transition-all duration-300 text-center transform hover:scale-105 animate-fade-in-heading"
                            style="animation-delay: 0.5s">
                            Start Your Journey
                        </button>
                        <button @click="showModal = true"
                            class="px-8 py-3 rounded-xl font-semibold border border-border text-foreground hover:bg-accent transition-all duration-300 text-center animate-fade-in-heading"
                            style="animation-delay: 0.5s">
                            Watch Video
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="grid grid-cols-3 gap-4 lg:gap-6 py-16 border-t border-border">
                <div v-for="(stat, index) in [
                    { value: '10K+', label: 'Active Users' },
                    { value: '500+', label: 'Learning Resources' },
                    { value: '99.9%', label: 'Uptime' },
                ]" :key="index" class="text-center group">
                    <p
                        class="text-4xl lg:text-5xl font-bold text-primary group-hover:text-primary/80 transition-all duration-300">
                        {{ stat.value }}
                    </p>
                    <p
                        class="text-muted-foreground text-sm mt-3 group-hover:text-foreground transition-colors duration-300 font-medium">
                        {{ stat.label }}
                    </p>
                </div>
            </div>

            <!-- Features Section -->
            <div class="mt-28">
                <div class="text-center mb-12 space-y-4 md:mb-16">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight">Why Choose LevelUp Academy?
                    </h2>
                    <p class="text-base md:text-lg text-muted-foreground max-w-2xl mx-auto font-light">
                        Everything you need to succeed in one powerful platform
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6">
                    <div
                        class="group p-4 md:p-8 rounded-xl md:rounded-2xl bg-card border border-border hover:shadow-xl hover:-translate-y-1 backdrop-blur-sm transition-all duration-500 cursor-default">
                        <Gamepad2
                            class="w-7 md:w-10 h-7 md:h-10 mb-2 md:mb-4 text-primary group-hover:scale-110 transition-transform duration-300" />
                        <h3
                            class="text-sm md:text-xl font-bold mb-2 md:mb-3 text-foreground group-hover:text-primary transition-colors">
                            Gamification
                        </h3>
                        <p class="text-xs md:text-sm text-muted-foreground leading-relaxed font-light">
                            Earn points, unlock badges, and build streaks to stay motivated
                        </p>
                    </div>
                    <div
                        class="group p-4 md:p-8 rounded-xl md:rounded-2xl bg-card border border-border hover:shadow-xl hover:-translate-y-1 backdrop-blur-sm transition-all duration-500 cursor-default">
                        <BarChart3
                            class="w-7 md:w-10 h-7 md:h-10 mb-2 md:mb-4 text-primary group-hover:scale-110 transition-transform duration-300" />
                        <h3
                            class="text-sm md:text-xl font-bold mb-2 md:mb-3 text-foreground group-hover:text-primary transition-colors">
                            Advanced Analytics
                        </h3>
                        <p class="text-xs md:text-sm text-muted-foreground leading-relaxed font-light">
                            Track progress with detailed insights and performance metrics
                        </p>
                    </div>
                    <div
                        class="group p-4 md:p-8 rounded-xl md:rounded-2xl bg-card border border-border hover:shadow-xl hover:-translate-y-1 backdrop-blur-sm transition-all duration-500 cursor-default md:col-span-1 col-span-2 sm:col-span-1">
                        <Award
                            class="w-7 md:w-10 h-7 md:h-10 mb-2 md:mb-4 text-primary group-hover:scale-110 transition-transform duration-300" />
                        <h3
                            class="text-sm md:text-xl font-bold mb-2 md:mb-3 text-foreground group-hover:text-primary transition-colors">
                            Leaderboards
                        </h3>
                        <p class="text-xs md:text-sm text-muted-foreground leading-relaxed font-light">
                            Compete with peers and earn recognition for achievements
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom CTA -->
            <div
                class="mt-28 py-16 px-8 rounded-3xl bg-primary text-primary-foreground text-center shadow-2xl relative overflow-hidden">
                <!-- Accent gradient -->
                <div class="absolute inset-0 opacity-20 bg-gradient-to-r from-white to-transparent" />
                <div class="relative z-10">
                    <h2 class="text-4xl lg:text-5xl font-bold mb-4 tracking-tight">Ready to Begin?</h2>
                    <p class="text-lg opacity-95 mb-8 max-w-2xl mx-auto font-light">
                        Join thousands of learners who are already transforming their skills
                    </p>
                    <button v-if="canRegister" @click="openVerification('register')"
                        class="inline-block px-8 py-3 rounded-xl font-semibold bg-primary-foreground text-primary hover:bg-primary-foreground/90 transition-all duration-300 transform hover:scale-105">
                        Create Free Account
                    </button>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-32 py-12 border-t border-border bg-background/80 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- Logo Column -->
                    <div class="flex items-start">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                                <span class="text-primary-foreground font-bold">✦</span>
                            </div>
                            <span class="font-bold text-lg text-primary">
                                LevelUp Academy
                            </span>
                        </div>
                    </div>

                    <!-- Links Column 1 -->
                    <div class="space-y-3">
                        <h4 class="font-semibold text-foreground text-sm">Product</h4>
                        <div class="space-y-2">
                            <a href="#"
                                class="text-muted-foreground hover:text-primary transition-colors text-sm">Features</a>
                            <a href="#"
                                class="block text-muted-foreground hover:text-primary transition-colors text-sm">Pricing</a>
                            <a href="#"
                                class="block text-muted-foreground hover:text-primary transition-colors text-sm">Security</a>
                        </div>
                    </div>

                    <!-- Links Column 2 -->
                    <div class="space-y-3">
                        <h4 class="font-semibold text-foreground text-sm">Company</h4>
                        <div class="space-y-2">
                            <a href="#"
                                class="block text-muted-foreground hover:text-primary transition-colors text-sm">Docs</a>
                            <a href="#"
                                class="block text-muted-foreground hover:text-primary transition-colors text-sm">Privacy</a>
                            <a href="#"
                                class="block text-muted-foreground hover:text-primary transition-colors text-sm">Contact</a>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-muted-foreground text-sm">
                        © 2025 LevelUp Academy. All rights reserved.
                    </p>
                    <div class="flex gap-6">
                        <a href="#"
                            class="text-muted-foreground hover:text-primary transition-colors text-sm font-medium">
                            Terms
                        </a>
                        <a href="#"
                            class="text-muted-foreground hover:text-primary transition-colors text-sm font-medium">
                            Privacy
                        </a>
                        <a href="#"
                            class="text-muted-foreground hover:text-primary transition-colors text-sm font-medium">
                            Contact
                        </a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Learn More Modal -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="showModal = false" />
                    <!-- Modal Content -->
                    <div class="relative bg-card border border-border rounded-2xl shadow-2xl p-8 max-w-md w-full">
                        <button @click="showModal = false"
                            class="absolute top-5 right-5 text-muted-foreground hover:text-foreground transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground mb-2">No Video</h2>
                            <p class="text-muted-foreground mb-6">Video coming soon...</p>
                            <button @click="showModal = false"
                                class="px-6 py-2 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors font-medium">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Auth Choice Modal (After Verification) -->
        <Teleport to="body">
            <Transition name="modal">
                <div v-if="showAuthChoice" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <!-- Backdrop -->
                    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" />
                    <!-- Modal Content -->
                    <div class="relative bg-card border border-border rounded-2xl shadow-2xl p-8 max-w-md w-full">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-foreground mb-2">Welcome!</h2>
                            <p class="text-muted-foreground mb-8">You're verified. What would you like to do?</p>
                            <div class="flex flex-col gap-3">
                                <button @click="redirectToAuth('login')"
                                    class="px-6 py-3 rounded-xl font-semibold bg-primary text-primary-foreground hover:bg-primary/90 transition-all duration-300 transform hover:scale-105">
                                    Log In
                                </button>
                                <button v-if="canRegister" @click="redirectToAuth('register')"
                                    class="px-6 py-3 rounded-xl font-semibold border border-primary text-primary hover:bg-primary/10 transition-all duration-300 transform hover:scale-105">
                                    Create Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- Verification Slider Modal -->
        <VerificationSlider v-model="showVerification" :redirect-to="verificationRedirectTo"
            @verified="handleVerified" />
    </div>
</template>

<style scoped>
@keyframes blob {

    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }

    33% {
        transform: translate(30px, -50px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

@keyframes fade-in-heading {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes rotate-in {
    from {
        opacity: 0;
        transform: translateY(100%);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}

.modal-enter-from {
    opacity: 0;
}

.modal-leave-to {
    opacity: 0;
}

.modal-enter-from :deep(.relative),
.modal-leave-to :deep(.relative) {
    transform: scale(0.95);
}

@keyframes float {

    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
    }

    50% {
        transform: translateY(-30px) rotate(5deg);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.perspective {
    perspective: 1000px;
}

.animate-fade-in-heading {
    animation: fade-in-heading 0.8s ease-out forwards;
}

.animate-rotate-in {
    animation: rotate-in 0.6s ease-out forwards;
}
</style>
