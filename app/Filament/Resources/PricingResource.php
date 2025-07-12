<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingResource\Pages;
use App\Filament\Resources\PricingResource\RelationManagers;
use App\Models\Pricing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class PricingResource extends Resource
{
    protected static ?string $model = Pricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = "Packages & Orders";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               FileUpload::make('icon')
                ->label('SVG Icon')
                ->image()
                ->directory('pricing-icons')
                ->acceptedFileTypes(['image/svg+xml'])
                ->maxSize(1024), // 1MB
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\TextInput::make('price')
                ->numeric()
                ->prefix('$')
                ->required(),
            Forms\Components\Textarea::make('short_description')->rows(3)->required(),
            Forms\Components\TextInput::make('special_tag')->placeholder('e.g. Most Popular'),
            Forms\Components\Repeater::make('features')
                ->schema([
                    Forms\Components\TextInput::make('feature')->label('Feature'),
                ])
                ->label('Features')
                ->addable()
                ->reorderable()
                ->deletable()
                ->columns(1)
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order', 'asc')   
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Icon')
                    ->disk('public')
                    ->height(30)
                    ->circular(false),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
                Tables\Columns\TextColumn::make('special_tag')->badge()->color('success'),
                Tables\Columns\TextColumn::make('short_description')->limit(50),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListPricings::route('/'),
            'create' => Pages\CreatePricing::route('/create'),
            'edit' => Pages\EditPricing::route('/{record}/edit'),
        ];
    }
}
