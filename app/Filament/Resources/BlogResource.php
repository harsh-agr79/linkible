<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Components\ {
    TextInput, Textarea, Select, FileUpload, RichEditor, DatePicker, MultiSelect, Toggle}
    ;
    use Filament\Forms\Form;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Tables\Table;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletingScope;

    class BlogResource extends Resource {
        protected static ?string $model = Blog::class;

        protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        public static function form( Form $form ): Form {
            return $form
            ->schema( [
                TextInput::make( 'title' )
                ->required()
                ->live( debounce: 500 )
                ->afterStateUpdated( fn ( $state, callable $set ) =>
                $set( 'slug', \Str::slug( $state ) )
            ),
            TextInput::make( 'slug' )
            ->required()
            ->unique( ignoreRecord: true )
            ->rules( [
                'regex:/^[a-zA-Z0-9_-]+$/'
            ] )
            ->helperText( 'Only letters, numbers, dashes (-), and underscores (_) are allowed. No spaces or special characters.' )
            ->maxLength( 255 ),
            Toggle::make('is_pinned')
            ->label('Pin this ' . ($record?->type ?? 'item'))
            ->helperText('Only one blog and one case study can be pinned at a time.')
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set, $get) {
                if ($state) {
                    // Automatically unpin others of the same type
                    \App\Models\Blog::where('type', $get('type'))
                        ->where('id', '!=', $get('id'))
                        ->update(['is_pinned' => false]);
                }
            }),
            TextInput::make( 'meta_title' )->required()->label( 'Meta Title' ),
            Textarea::make( 'meta_description' )->required()->label( 'Meta Description' )->rows( 3 ),
            RichEditor::make( 'content' )
            ->toolbarButtons( [
                'attachFiles',
                'blockquote',
                'bold',
                'bulletList',
                'codeBlock',
                'h1',
                'h2',
                'h3',
                'italic',
                'link',
                'orderedList',
                'redo',
                'strike',
                'underline',
                'undo',
            ] )->required()->columnSpanFull(),
            Select::make( 'type' )
            ->options( [
                'blog' => 'Blog',
                'case_study' => 'Case Study',
            ] )
            ->required(),
            DatePicker::make( 'published_at' )->required()->label( 'Published Date' ),
            FileUpload::make( 'cover_image' )
            ->label( 'Cover Image' )
            ->disk( 'public' )
            ->directory( 'blog-covers' )
            ->image()
            ->imagePreviewHeight( 150 ),
            MultiSelect::make( 'recommendations' )
            ->label( 'Recommended Posts' )
            ->options( Blog::all()->pluck( 'title', 'id' ) )
            ->preload(),
        ] );
    }

    public static function table( Table $table ): Table {
        return $table
        ->defaultSort( 'published_at', 'desc' )
        ->poll( '10s' )
        ->columns( [
            Tables\Columns\ImageColumn::make('cover_image')
            ->label('Cover')
            ->disk('public') // adjust if using a different disk
            ->height(60)
            ->width(60)
            ->circular(false), // or true if you want round images
            Tables\Columns\TextColumn::make( 'title' )->searchable()->sortable(),
            Tables\Columns\TextColumn::make( 'type' )->badge(),
            Tables\Columns\BooleanColumn::make( 'is_pinned' ),
            Tables\Columns\TextColumn::make( 'published_at' )->date(),
        ] )
        ->filters( [
            //
        ] )
        ->actions( [
            Tables\Actions\EditAction::make(),
        ] )
        ->bulkActions( [
            Tables\Actions\BulkActionGroup::make( [
                Tables\Actions\DeleteBulkAction::make(),
            ] ),
        ] );
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListBlogs::route( '/' ),
            'create' => Pages\CreateBlog::route( '/create' ),
            'edit' => Pages\EditBlog::route( '/{record}/edit' ),
        ];
    }
}
