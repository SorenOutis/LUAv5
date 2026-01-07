<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $currentUser = Auth::user();

        return [
            Actions\Action::make('impersonate')
                ->label('Impersonate')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Impersonate User')
                ->modalDescription("Are you sure you want to impersonate {$this->record->name}? This will log you in as this user and you'll be able to see the dashboard from their perspective.")
                ->modalSubmitActionLabel('Yes, impersonate')
                ->modalCancelActionLabel('Cancel')
                ->visible(fn (): bool => $currentUser && $currentUser->hasRole('admin') && $this->record->id !== $currentUser->id)
                ->action(function () {
                    $impersonatedUser = $this->record;

                    session()->put('impersonator_id', Auth::id());
                    Auth::login($impersonatedUser, remember: true);

                    $this->redirect(route('dashboard'));
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
