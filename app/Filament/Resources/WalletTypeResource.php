<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WalletTypeResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class WalletTypeResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Wallet Types';

    protected static ?string $modelLabel = 'Wallet Type';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'master/wallet-types';

    public const TYPE_CREDIT_CARD = 'credit_card';

    public const NATURE_ASSET = 'asset';
    public const NATURE_LIABILITY = 'liability';

    /**
     * Wallet type categories, mirroring model.WalletType in the wallet service.
     * Single source of truth for the selects, the validation rules and the
     * table filter, which previously drifted apart across five places.
     */
    public static function types(): array
    {
        return [
            'bank' => 'Bank',
            'e-wallet' => 'E-Wallet',
            'physical' => 'Physical',
            self::TYPE_CREDIT_CARD => 'Credit Card',
            'others' => 'Others',
        ];
    }

    /**
     * Whether the wallet holds money or is a credit line. For a liability the
     * balance is the credit still available, so it is kept out of net worth.
     */
    public static function natures(): array
    {
        return [
            self::NATURE_ASSET => 'Asset (holds money)',
            self::NATURE_LIABILITY => 'Liability (credit line)',
        ];
    }

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

    /**
     * NOTE: this schema and table() below are not rendered. The Create/Edit/List
     * pages extend the plain Page class with their own Blade views and Livewire
     * props, so the real UI lives in resources/views/filament/resources/master-data/.
     * They are kept in sync only so the next reader is not misled.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Wallet Type Information')
                    ->description('Manage wallet type master data. Changes are sent to the wallet service via message queue.')
                    ->icon('heroicon-o-wallet')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('e.g. BCA, GoPay, Cash'),

                        Forms\Components\Select::make('type')
                            ->label('Type')
                            ->required()
                            ->options(self::types()),

                        Forms\Components\Select::make('nature')
                            ->label('Nature')
                            ->required()
                            ->default(self::NATURE_ASSET)
                            ->options(self::natures()),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Optional description')
                            ->rows(3),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->copyable()
                    ->limit(8),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->sortable()
                    ->colors([
                        'primary'   => 'bank',
                        'success'   => 'e-wallet',
                        'warning'   => 'physical',
                        'danger'    => self::TYPE_CREDIT_CARD,
                        'secondary' => 'others',
                    ]),

                Tables\Columns\BadgeColumn::make('nature')
                    ->label('Nature')
                    ->sortable()
                    ->colors([
                        'success' => self::NATURE_ASSET,
                        'danger'  => self::NATURE_LIABILITY,
                    ]),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No wallet types found')
            ->emptyStateDescription('Wallet types will be fetched from the wallet service.')
            ->emptyStateIcon('heroicon-o-wallet');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWalletTypes::route('/'),
            'create' => Pages\CreateWalletType::route('/create'),
            'edit'   => Pages\EditWalletType::route('/{record}/edit'),
        ];
    }
}
