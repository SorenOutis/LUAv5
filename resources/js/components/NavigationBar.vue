<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Menu, X, ArrowRight, Moon, Sun } from 'lucide-vue-next';
import { Link, router } from '@inertiajs/vue3';
import { useLogo } from '@/composables/useLogo';
import { useAppearance } from '@/composables/useAppearance';
import { dashboard, login, register } from '@/routes';

interface NavItem {
    name: string;
    href: string;
}

const navigation: NavItem[] = [
    { name: 'Features', href: '#features' },
    { name: 'Pricing', href: '#pricing' },
    { name: 'About', href: '#about' },
];

const isOpen = ref(false);
const isVisible = ref(true);
const hasLoaded = ref(false);
const lastScrollY = ref(0);
const { hasLogo, logo, getLightLogo, getDarkLogo } = useLogo();
const { appearance, updateAppearance } = useAppearance();

const isDarkMode = computed(() => {
    if (appearance.value === 'system') {
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    return appearance.value === 'dark';
});

const toggleTheme = () => {
    if (isDarkMode.value) {
        updateAppearance('light');
    } else {
        updateAppearance('dark');
    }
};

onMounted(() => {
    setTimeout(() => {
        hasLoaded.value = true;
    }, 100);

    const controlNavbar = () => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > 50) {
            if (currentScrollY > lastScrollY.value && currentScrollY - lastScrollY.value > 5) {
                isVisible.value = false;
            } else if (lastScrollY.value - currentScrollY > 5) {
                isVisible.value = true;
            }
        } else {
            isVisible.value = true;
        }

        lastScrollY.value = currentScrollY;
    };

    window.addEventListener('scroll', controlNavbar, { passive: true });

    onUnmounted(() => {
        window.removeEventListener('scroll', controlNavbar);
    });
});

const scrollToSection = (href: string) => {
    if (href.startsWith('/')) {
        return;
    }

    const element = document.querySelector(href);
    if (element) {
        const rect = element.getBoundingClientRect();
        const currentScrollY = window.pageYOffset || document.documentElement.scrollTop;
        const elementAbsoluteTop = rect.top + currentScrollY;
        const navbarHeight = 100;
        const targetPosition = Math.max(0, elementAbsoluteTop - navbarHeight);

        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth',
        });
    }
    isOpen.value = false;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
    <nav :class="`fixed top-4 md:top-8 left-1/2 -translate-x-1/2 z-50 transition-all duration-500 ${isVisible ? 'translate-y-0 opacity-100' : '-translate-y-20 md:-translate-y-24 opacity-0'
        } ${hasLoaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`" :style="{
            transition: hasLoaded ? 'all 0.5s ease-out' : 'opacity 0.8s ease-out, transform 0.8s ease-out',
        }">
        <!-- Main Navigation -->
        <div class="w-[90vw] max-w-xs md:max-w-4xl mx-auto">
            <div
                class="bg-background/80 backdrop-blur-xl border border-border rounded-full px-4 py-3 md:px-6 md:py-2 shadow-lg">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <button @click="scrollToTop"
                        class="flex items-center hover:scale-105 transition-transform duration-200 cursor-pointer">
                        <div v-if="hasLogo && logo"
                            class="w-10 h-10 md:w-12 md:h-12 rounded-full overflow-hidden flex-shrink-0">
                            <img v-if="getDarkLogo()" :src="getDarkLogo()" :alt="logo.name"
                                class="w-full h-full object-cover" />
                            <img v-else-if="getLightLogo()" :src="getLightLogo()" :alt="logo.name"
                                class="w-full h-full object-cover" />
                            <div v-else
                                class="w-full h-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                <span class="text-primary-foreground font-bold">✦</span>
                            </div>
                        </div>
                        <div v-else
                            class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                            <span class="text-primary-foreground font-bold text-lg">✦</span>
                        </div>
                    </button>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <button v-for="item in navigation" :key="item.name" @click="scrollToSection(item.href)"
                            class="text-foreground/80 hover:text-foreground hover:scale-105 transition-all duration-200 font-medium cursor-pointer">
                            {{ item.name }}
                        </button>
                    </div>

                    <!-- Desktop CTA Button -->
                    <div class="hidden md:block">
                        <Link v-if="$page.props.auth.user" :href="dashboard()"
                            class="relative bg-primary hover:bg-primary/90 text-primary-foreground font-medium px-6 py-2 rounded-full flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer group">
                            <span class="mr-2">Dashboard</span>
                            <ArrowRight :size="16"
                                class="transition-transform duration-300 group-hover:translate-x-1" />
                        </Link>
                        <button v-else @click="$emit('open-verification', 'register')"
                            class="relative bg-primary hover:bg-primary/90 text-primary-foreground font-medium px-6 py-2 rounded-full flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer group">
                            <span class="mr-2">Get Started</span>
                            <ArrowRight :size="16"
                                class="transition-transform duration-300 group-hover:translate-x-1" />
                        </button>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="isOpen = !isOpen"
                        class="md:hidden text-foreground hover:scale-110 transition-transform duration-200 cursor-pointer">
                        <div class="relative w-6 h-6">
                            <Menu :size="24" :class="`absolute inset-0 transition-all duration-300 ${isOpen ? 'opacity-0 rotate-180 scale-75' : 'opacity-100 rotate-0 scale-100'
                                }`" />
                            <X :size="24" :class="`absolute inset-0 transition-all duration-300 ${isOpen ? 'opacity-100 rotate-0 scale-100' : 'opacity-0 -rotate-180 scale-75'
                                }`" />
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden relative">
            <!-- Backdrop overlay -->
            <div :class="`fixed inset-0 bg-black/20 backdrop-blur-sm transition-all duration-300 ${isOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'
                }`" @click="isOpen = false" style="top: 0; left: 0; right: 0; bottom: 0; z-index: -1" />

            <!-- Menu container -->
            <div :class="`mt-2 w-[90vw] max-w-xs mx-auto transition-all duration-500 ease-out transform-gpu ${isOpen ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 -translate-y-8 scale-95 pointer-events-none'
                }`">
                <div class="bg-background/80 backdrop-blur-xl border border-border rounded-2xl p-4 shadow-2xl">
                    <div class="flex flex-col space-y-1">
                        <button v-for="(item, index) in navigation" :key="item.name" @click="scrollToSection(item.href)"
                            :style="{
                                animationDelay: isOpen ? `${index * 80 + 100}ms` : '0ms',
                            }"
                            class="text-foreground/80 hover:text-foreground hover:bg-accent rounded-lg px-3 py-3 text-left transition-all duration-300 font-medium cursor-pointer transform hover:scale-[1.02] hover:translate-x-1">
                            {{ item.name }}
                        </button>
                        <button @click="$emit('toggle-theme')" :style="{
                            animationDelay: isOpen ? `${(navigation.length) * 80 + 100}ms` : '0ms',
                        }" class="w-full text-foreground/80 hover:text-foreground hover:bg-accent rounded-lg px-3 py-3 text-left transition-all duration-300 font-medium cursor-pointer transform hover:scale-[1.02] hover:translate-x-1 flex items-center gap-2"
                            :aria-label="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                            <Moon v-if="isDarkMode" :size="18" />
                            <Sun v-else :size="18" />
                            <span>{{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}</span>
                        </button>
                        <div class="h-px bg-border my-2" />
                        <Link v-if="$page.props.auth.user" :href="dashboard()" :style="{
                            animationDelay: isOpen ? `${(navigation.length + 1) * 80 + 100}ms` : '0ms',
                        }" class="relative bg-primary hover:bg-primary/90 text-primary-foreground font-medium px-6 py-3 rounded-full flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer group transform"
                            @click="isOpen = false">
                            <span class="mr-2">Dashboard</span>
                            <ArrowRight :size="16"
                                class="transition-transform duration-300 group-hover:translate-x-1" />
                        </Link>
                        <button v-else @click="$emit('open-verification', 'register')" :style="{
                            animationDelay: isOpen ? `${(navigation.length + 1) * 80 + 100}ms` : '0ms',
                        }"
                            class="relative bg-primary hover:bg-primary/90 text-primary-foreground font-medium px-6 py-3 rounded-full flex items-center transition-all duration-300 hover:scale-105 hover:shadow-lg cursor-pointer group transform">
                            <span class="mr-2">Get Started</span>
                            <ArrowRight :size="16"
                                class="transition-transform duration-300 group-hover:translate-x-1" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>
