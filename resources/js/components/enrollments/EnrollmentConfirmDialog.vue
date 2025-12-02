<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';

interface Props {
    open: boolean;
    courseTitle: string;
    courseId: number;
    isLoading?: boolean;
}

interface Emits {
    (e: 'close'): void;
    (e: 'confirm'): void;
}

defineProps<Props>();
defineEmits<Emits>();
</script>

<template>
    <Dialog :open="open" @update:open="$emit('close')">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Confirm Enrollment</DialogTitle>
                <DialogDescription>
                    <div class="space-y-4 py-4">
                        <div>
                            <p class="font-semibold">{{ courseTitle }}</p>
                            <p class="mt-2 text-sm text-muted-foreground">
                                You are about to request enrollment in this course. Your teacher will review your
                                request and approve it soon. You'll be notified once your enrollment is approved.
                            </p>
                        </div>
                    </div>
                </DialogDescription>
            </DialogHeader>
            <div class="flex justify-end gap-2">
                <Button variant="outline" @click="$emit('close')" :disabled="isLoading">Cancel</Button>
                <Button @click="$emit('confirm')" :disabled="isLoading">
                    {{ isLoading ? 'Requesting...' : 'Request Enrollment' }}
                </Button>
            </div>
        </DialogContent>
    </Dialog>
</template>
