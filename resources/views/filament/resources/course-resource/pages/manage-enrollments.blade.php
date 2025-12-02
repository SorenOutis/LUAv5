@php
    use Filament\Facades\Filament;
@endphp

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Enrolled Students</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Managing enrollments for "{{ $this->record->title }}"</p>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament-panels::page>
