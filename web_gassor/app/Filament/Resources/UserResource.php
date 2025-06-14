<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('username')->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                Forms\Components\TextInput::make('phone')->maxLength(20),
                Forms\Components\Select::make('role')
                    ->options([
                        'pemilik' => 'Pemilik',
                        'penyewa' => 'Penyewa',
                    ])->required(),
                Forms\Components\DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
                Forms\Components\TextInput::make('tempat_lahir')->label('Tempat Lahir')->maxLength(255),
                Forms\Components\FileUpload::make('profile_image_url')
                    ->label('Foto Profil')
                    ->image()
                    ->directory('profile_images')
                    ->disk('public'),
                Forms\Components\FileUpload::make('ktp_image_url')
                    ->label('Foto KTP')
                    ->image()
                    ->directory('ktp_images')
                    ->disk('public'),
                Forms\Components\FileUpload::make('sim_image_url')
                    ->label('Foto SIM')
                    ->image()
                    ->directory('sim_images')
                    ->disk('public'),
                Forms\Components\FileUpload::make('ktm_image_url')
                    ->label('Foto KTM')
                    ->image()
                    ->directory('ktm_images')
                    ->disk('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('username')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('role'),
                TextColumn::make('phone'),
                TextColumn::make('tempat_lahir'),
                TextColumn::make('tanggal_lahir')->date(),
                ImageColumn::make('profile_image_url')->label('Foto Profil')->disk('public'),
                ImageColumn::make('ktp_image_url')->label('KTP')->disk('public'),
                ImageColumn::make('sim_image_url')->label('SIM')->disk('public'),
                ImageColumn::make('ktm_image_url')->label('KTM')->disk('public'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'pemilik' => 'Pemilik',
                        'penyewa' => 'Penyewa',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
}
