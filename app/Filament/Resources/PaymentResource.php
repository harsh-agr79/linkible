<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextareaColumn;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('payment_intent_id')
                    ->label('Intent ID')
                    ->copyable()
                    ->searchable()
                    ->wrap(),
                TextColumn::make('session_id')
                    ->label('Session ID')
                    ->copyable()
                    ->searchable()
                    ->wrap(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'succeeded',
                        'danger' => 'failed',
                        'warning' => ['processing', 'requires_payment_method'],
                        'gray' => 'incomplete',
                    ])
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount ($)')
                    ->money('usd', divideBy: 100)
                    ->sortable(),

                TextColumn::make('currency'),

                TextColumn::make('email')
                    ->label('Customer Email')
                    ->searchable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPayments::route('/'),
            // 'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
