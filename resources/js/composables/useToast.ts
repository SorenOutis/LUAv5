import { ref } from 'vue';

export interface Toast {
    id: string;
    type: 'achievement' | 'quest' | 'levelup' | 'xp' | 'info' | 'success' | 'error';
    title: string;
    message?: string;
    icon?: string;
    duration?: number;
    data?: Record<string, any>;
}

const toasts = ref<Toast[]>([]);
let toastCounter = 0;

export function useToast() {
    const addToast = (toast: Omit<Toast, 'id'>) => {
        const id = `toast-${++toastCounter}`;
        const newToast: Toast = {
            ...toast,
            id,
            duration: toast.duration ?? 4000,
        };

        toasts.value.push(newToast);

        if (newToast.duration && newToast.duration > 0) {
            setTimeout(() => {
                removeToast(id);
            }, newToast.duration);
        }

        return id;
    };

    const removeToast = (id: string) => {
        const index = toasts.value.findIndex(t => t.id === id);
        if (index !== -1) {
            toasts.value.splice(index, 1);
        }
    };

    const achievement = (title: string, message?: string, icon?: string) => {
        return addToast({
            type: 'achievement',
            title,
            message,
            icon: icon || '⭐',
            duration: 5000,
        });
    };

    const quest = (title: string, message?: string) => {
        return addToast({
            type: 'quest',
            title,
            message,
            icon: '✓',
            duration: 5000,
        });
    };

    const levelup = (level: number, xpGained?: number) => {
        return addToast({
            type: 'levelup',
            title: `Level Up!`,
            message: `You reached level ${level}${xpGained ? ` (+${xpGained} XP)` : ''}`,
            icon: '⚡',
            duration: 6000,
            data: { level, xpGained },
        });
    };

    const xp = (amount: number, source?: string) => {
        return addToast({
            type: 'xp',
            title: `+${amount} XP`,
            message: source ? `from ${source}` : undefined,
            icon: '✨',
            duration: 3000,
            data: { amount, source },
        });
    };

    const info = (title: string, message?: string) => {
        return addToast({
            type: 'info',
            title,
            message,
            duration: 4000,
        });
    };

    const success = (title: string, message?: string) => {
        return addToast({
            type: 'success',
            title,
            message,
            duration: 4000,
        });
    };

    const error = (title: string, message?: string) => {
        return addToast({
            type: 'error',
            title,
            message,
            duration: 5000,
        });
    };

    return {
        toasts,
        addToast,
        removeToast,
        achievement,
        quest,
        levelup,
        xp,
        info,
        success,
        error,
    };
}
