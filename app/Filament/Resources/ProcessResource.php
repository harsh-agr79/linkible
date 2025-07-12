<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcessResource\Pages;
use App\Filament\Resources\ProcessResource\RelationManagers;
use App\Models\Process;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{TextInput, Textarea, Repeater};
use Filament\Tables\Columns\{TextColumn, ImageColumn};

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = "Home Page";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
             TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('short_title')
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535),
                TextInput::make('icon')
                    ->maxLength(255)
                    ->placeholder('e.g., heroicon-o-cog'),
                TextInput::make('order')
                    ->numeric()
                    ->default(0),
                Repeater::make('bullets')
                    ->schema([
                        TextInput::make('value')
                            ->label('Bullet Point')
                            ->required(),
                    ])
                    ->default([])
                    ->label('Bullet Points')
                    ->columns(1)
                    ->addable()
                    ->deletable()
                    ->reorderable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->reorderable('order')
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('short_title')->sortable(),
            ])
              ->defaultSort('order')
            ->filters([
                //
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
            'index' => Pages\ListProcesses::route('/'),
            'create' => Pages\CreateProcess::route('/create'),
            'edit' => Pages\EditProcess::route('/{record}/edit'),
        ];
    }
}
