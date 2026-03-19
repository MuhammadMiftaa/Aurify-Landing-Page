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
                            ->options([
                                'bank'     => 'Bank',
                                'e-wallet' => 'E-Wallet',
                                'physical' => 'Physical',
                                'others'   => 'Others',
                            ]),

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
                        'secondary' => 'others',
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
