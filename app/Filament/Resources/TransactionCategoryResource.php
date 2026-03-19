<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionCategoryResource\Pages;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class TransactionCategoryResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Transaction Categories';

    protected static ?string $modelLabel = 'Transaction Category';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'master/transaction-categories';

    public static function canAccess(): bool
    {
        return Auth::user()?->isAdmin() || Auth::user()?->isSuperadmin() ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->isSuperadmin() ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->isSuperadmin() ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->isSuperadmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->isSuperadmin() ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTransactionCategories::route('/'),
            'create' => Pages\CreateTransactionCategory::route('/create'),
            'edit'   => Pages\EditTransactionCategory::route('/{record}/edit'),
        ];
    }
}
