import { ref, computed, onUnmounted } from 'vue';
import axios from 'axios';

export interface DynamicNotification {
    id: number;
    type: 'course' | 'leaderboard' | 'achievement' | 'reward' | 'community' | 'assignment' | 'announcement' | 'community_post' | 'xp' | 'streak' | 'level_up';
    title: string;
    message: string;
    timestamp: string;
    read: boolean;
    icon: string;
    data?: Record<string, any>;
}

// Initialize from localStorage or as empty
const getInitialNotifications = (): DynamicNotification[] => {
    try {
        const stored = localStorage.getItem('app_notifications');
        return stored ? JSON.parse(stored) : [];
    } catch {
        return [];
    }
};

const notifications = ref<DynamicNotification[]>(getInitialNotifications());
const isLoading = ref(false);
const refreshInterval = ref<NodeJS.Timeout | null>(null);
const lastNotificationCount = ref(notifications.value.length);

export function useNotifications() {
    const unreadCount = computed(() => 
        notifications.value.filter(n => !n.read).length
    );

    const saveNotificationsToStorage = (notifs: DynamicNotification[]) => {
        try {
            localStorage.setItem('app_notifications', JSON.stringify(notifs));
        } catch (error) {
            console.error('Failed to save notifications to localStorage:', error);
        }
    };

    const sendBrowserNotification = (title: string, options?: NotificationOptions) => {
        if ('Notification' in window && Notification.permission === 'granted') {
            try {
                new Notification(title, {
                    icon: '/notification-icon.png',
                    ...options,
                });
            } catch (error) {
                console.error('Failed to send browser notification:', error);
            }
        }
    };

    const fetchNotifications = async () => {
        isLoading.value = true;
        try {
            const [notificationsResponse, announcementsResponse] = await Promise.all([
                axios.get('/api/notifications'),
                axios.get('/api/announcements-and-posts/latest').catch(() => ({ data: [] })),
            ]);
            
            console.log('Fetched notifications:', notificationsResponse.data);
            console.log('Fetched announcements:', announcementsResponse.data);
            
            // Transform database notifications to match interface
            const dbNotifications = (notificationsResponse.data || []).map((n: any) => ({
                id: n.id,
                type: n.type || 'notification',
                title: n.title,
                message: n.message,
                timestamp: n.timestamp || n.createdAt || new Date().toLocaleString(),
                read: !!n.read,
                icon: n.icon || '📧',
                data: n.data || {},
            }));

            // Transform announcements
            const announcementNotifications = (announcementsResponse.data || []).map((a: any) => ({
                ...a,
                read: a.read || false,
                timestamp: a.timestamp || new Date().toLocaleString(),
            }));
            
            // Combine notifications with announcements and community posts
            const combined = [
                ...dbNotifications,
                ...announcementNotifications,
            ];
            
            console.log('Combined notifications:', combined);
            
            // Sort by most recent
            combined.sort((a, b) => {
                const dateA = new Date(a.timestamp).getTime();
                const dateB = new Date(b.timestamp).getTime();
                return dateB - dateA;
            });
            
            // Check for new notifications (achievement, xp, streak, level_up)
            const newNotifications = combined.filter(n => !notifications.value.some(existing => existing.id === n.id && existing.type === n.type));
            const importantNewNotifications = newNotifications.filter(n => 
                ['achievement', 'xp', 'streak', 'level_up', 'reward'].includes(n.type)
            );
            
            // Send browser notifications for important new notifications
            importantNewNotifications.forEach(notification => {
                sendBrowserNotification(notification.title, {
                    body: notification.message,
                    badge: '✨',
                });
            });
            
            notifications.value = combined;
            lastNotificationCount.value = combined.length;
            saveNotificationsToStorage(combined);
            isLoading.value = false;
            
            console.log('Final notifications state:', notifications.value);
            console.log('Unread count:', unreadCount.value);
        } catch (error) {
            console.error('Failed to fetch notifications:', error);
            isLoading.value = false;
        }
    };

    const requestNotificationPermission = async () => {
        if ('Notification' in window && Notification.permission === 'default') {
            try {
                await Notification.requestPermission();
            } catch (error) {
                console.error('Failed to request notification permission:', error);
            }
        }
    };

    const setupRefreshInterval = () => {
        // Clear existing interval
        if (refreshInterval.value) {
            clearInterval(refreshInterval.value);
        }

        // Request notification permission
        requestNotificationPermission();

        // Fetch initial notifications
        fetchNotifications();

        // Set up polling for real-time updates (2 seconds for instant feedback on XP, achievements, streak)
        refreshInterval.value = setInterval(() => {
            fetchNotifications();
        }, 2000);

        // Handle visibility changes - pause refresh when tab is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (refreshInterval.value) {
                    clearInterval(refreshInterval.value);
                    refreshInterval.value = null;
                }
            } else {
                // Resume refresh when tab becomes visible
                fetchNotifications();
                refreshInterval.value = setInterval(() => {
                    fetchNotifications();
                }, 2000);
            }
        });
    };

    const setupEventStream = setupRefreshInterval;

    const markAsRead = async (notificationId: number) => {
        // Update frontend state immediately
        const notification = notifications.value.find(n => n.id === notificationId);
        if (notification) {
            notification.read = true;
        }

        // Persist to storage
        saveNotificationsToStorage(notifications.value);

        // Handle different notification types
        if (notificationId >= 10000) {
            // Community post
            try {
                await axios.post('/api/announcements/mark-read', {
                    announcement_id: notificationId - 10000,
                    type: 'community_post',
                });
            } catch (error) {
                console.error('Failed to mark community post as read:', error);
            }
        } else if (notification?.type === 'announcement') {
            // Announcement
            try {
                await axios.post('/api/announcements/mark-read', {
                    announcement_id: notificationId,
                    type: 'announcement',
                });
            } catch (error) {
                console.error('Failed to mark announcement as read:', error);
            }
        } else {
            // Regular notification
            try {
                await axios.post(`/api/notifications/${notificationId}/read`);
            } catch (error) {
                console.error('Failed to mark notification as read:', error);
            }
        }
    };

    const deleteNotification = async (notificationId: number) => {
        try {
            await axios.delete(`/api/notifications/${notificationId}`);
            notifications.value = notifications.value.filter(n => n.id !== notificationId);
            saveNotificationsToStorage(notifications.value);
        } catch (error) {
            console.error('Failed to delete notification:', error);
        }
    };

    const markAllAsRead = async () => {
        // Mark all notifications in the frontend state
        notifications.value.forEach(n => n.read = true);
        
        // Persist to storage
        saveNotificationsToStorage(notifications.value);
        
        // Prepare items to mark as read
        const announcementItems = notifications.value.filter(n => n.type === 'announcement' || n.type === 'community_post');
        
        try {
            // Mark announcements and community posts as read
            if (announcementItems.length > 0) {
                await axios.post('/api/announcements/mark-all-read', {
                    items: announcementItems.map(item => ({
                        id: item.type === 'community_post' ? item.id - 10000 : item.id,
                        type: item.type,
                    })),
                });
            }
            
            // Mark all regular notifications as read in the database
            await axios.post('/api/notifications/mark-all-read');
        } catch (error) {
            console.error('Failed to mark all as read:', error);
        }
    };

    const getNotificationsByType = (type: DynamicNotification['type']) => {
        return notifications.value.filter(n => n.type === type);
    };

    // Cleanup on unmount
    onUnmounted(() => {
        if (refreshInterval.value) {
            clearInterval(refreshInterval.value);
            refreshInterval.value = null;
        }
    });

    return {
        notifications,
        unreadCount,
        isLoading,
        fetchNotifications,
        setupEventStream,
        markAsRead,
        deleteNotification,
        markAllAsRead,
        getNotificationsByType,
    };
}
