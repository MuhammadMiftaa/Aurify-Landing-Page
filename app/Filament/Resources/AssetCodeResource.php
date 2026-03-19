<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetCodeResource\Pages;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;

class AssetCodeResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Asset Codes';

    protected static ?string $modelLabel = 'Asset Code';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'master/asset-codes';

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
            'index'  => Pages\ListAssetCodes::route('/'),
            'create' => Pages\CreateAssetCode::route('/create'),
            'edit'   => Pages\EditAssetCode::route('/{record}/edit'),
        ];
    }
}
