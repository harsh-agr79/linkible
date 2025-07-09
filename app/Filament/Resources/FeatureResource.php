<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeatureResource\Pages;
use App\Filament\Resources\FeatureResource\RelationManagers;
use App\Models\Feature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class FeatureResource extends Resource
{
    protected static ?string $model = Feature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('description')
                    ->nullable()
                    ->maxLength(65535),

                Toggle::make('linkible_boolean')
                    ->label('Is Linkible Boolean?')
                    ->reactive(),

                Forms\Components\Group::make([
                    Toggle::make('linkible')
                        ->label('Linkible (Boolean)')
                        ->visible(fn ($get) => $get('linkible_boolean')),

                    TextInput::make('linkible')
                        ->label('Linkible (Text)')
                        ->visible(fn ($get) => ! $get('linkible_boolean')),
                ]),

                Toggle::make('other_agencies_boolean')
                    ->label('Is Other Agencies Boolean?')
                    ->reactive(),

                Forms\Components\Group::make([
                    Toggle::make('other_agencies')
                        ->label('Other Agencies (Boolean)')
                        ->visible(fn ($get) => $get('other_agencies_boolean')),

                    TextInput::make('other_agencies')
                        ->label('Other Agencies (Text)')
                        ->visible(fn ($get) => ! $get('other_agencies_boolean')),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\BooleanColumn::make('linkible_boolean'),
                Tables\Columns\TextColumn::make('linkible'),
                Tables\Columns\BooleanColumn::make('other_agencies_boolean'),
                Tables\Columns\TextColumn::make('other_agencies'),
            ])
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
            'index' => Pages\ListFeatures::route('/'),
            'create' => Pages\CreateFeature::route('/create'),
            'edit' => Pages\EditFeature::route('/{record}/edit'),
        ];
    }
}
