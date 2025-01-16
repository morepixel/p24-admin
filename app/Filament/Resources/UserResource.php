<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('firstname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lastname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->required()
                    ->options([
                        'lawyer' => 'Anwalt',
                        'assistant' => 'Sachbearbeiter',
                    ]),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Forms\Components\TextInput::make('language')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('street')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('companyName')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('companyForm')
                    ->maxLength(255),
                Forms\Components\TextInput::make('companyOfficer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('companyNumber')
                    ->maxLength(255),
                Forms\Components\TextInput::make('companyCourt')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lawyer' => 'success',
                        'assistant' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('companyName')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastActivity')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdAt')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'lawyer' => 'Anwalt',
                        'assistant' => 'Sachbearbeiter',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Verwaltung';
    }

    public static function getNavigationLabel(): string
    {
        return 'Benutzer';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'lawyer';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'lawyer';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->role === 'lawyer';
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->role === 'lawyer';
    }
}
