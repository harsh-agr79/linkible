<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutResource\Pages;
use App\Filament\Resources\AboutResource\RelationManagers;
use App\Models\About;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class AboutResource extends Resource
{
    protected static ?string $model = About::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'About Us';

    protected static ?string $navigationGroup = "Pages";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('meta_title')->required(),
                TextInput::make('meta_description')->required(),
                FileUpload::make('meta_image')->required()->image(),

                TextInput::make('hero_title')->required(),
                Textarea::make('hero_description')->required(),

                TextInput::make('happy_customers')->required(),
                TextInput::make('team_members_count')->required(),
                TextInput::make('uptime')->required(),
                TextInput::make('countries')->required(),

                Textarea::make('our_story')->required(),
                FileUpload::make('story_image')->required()->image(),

                Repeater::make('values')
                    ->schema([
                        TextInput::make('title')->required(),
                        FileUpload::make('icon')->image()->required(),
                        Textarea::make('description')->required(),
                    ])
                    ->columnSpanFull()
                    ->default([])
                    ->label('Our Values'),

                Repeater::make('team')
                    ->schema([
                        FileUpload::make('image')->image()->required(),
                        TextInput::make('name')->required(),
                        TextInput::make('designation')->required(),
                        Textarea::make('description')->required(),
                    ])
                    ->columnSpanFull()
                    ->default([]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('meta_title'),
                TextColumn::make('hero_title'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

      public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbouts::route('/'),
            'create' => Pages\CreateAbout::route('/create'),
            'edit' => Pages\EditAbout::route('/{record}/edit'),
        ];
    }
}
