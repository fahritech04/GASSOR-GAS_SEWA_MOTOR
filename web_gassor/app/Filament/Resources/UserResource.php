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
                Forms\Components\Toggle::make('is_approved')
                    ->label('Disetujui Admin?')
                    ->helperText('Centang jika data sudah diverifikasi dan disetujui admin. PENTING: Jika status pemilik diubah menjadi tidak disetujui, semua motor miliknya akan disembunyikan dari halaman penyewa.'),
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
                TextColumn::make('motorcycles_count')
                    ->label('Jumlah Motor')
                    ->counts('motorcycles')
                    ->badge()
                    ->color('success'),
                TextColumn::make('active_transactions_count')
                    ->label('Transaksi Aktif')
                    ->getStateUsing(function (User $record) {
                        return \App\Models\Transaction::whereHas('motorcycle', function ($query) use ($record) {
                            $query->where('owner_id', $record->id);
                        })->whereIn('rental_status', ['pending', 'on_going'])->count();
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'danger' : 'success'),
                TextColumn::make('phone'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean()
                    ->label('Approved'),
                ImageColumn::make('profile_image_url')->label('Foto Profil')->disk('public'),
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
                Tables\Actions\DeleteAction::make()
                    ->before(function (User $record) {
                        $activeTransactions = \App\Models\Transaction::whereHas('motorcycle', function ($query) use ($record) {
                            $query->where('owner_id', $record->id);
                        })->whereIn('rental_status', ['pending', 'on_going'])->exists();

                        if ($activeTransactions) {
                            throw new \Exception('Tidak dapat menghapus user karena masih memiliki transaksi aktif. Selesaikan atau batalkan transaksi terlebih dahulu.');
                        }

                        \App\Models\Transaction::whereHas('motorcycle', function ($query) use ($record) {
                            $query->where('owner_id', $record->id);
                        })->delete();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Hapus User')
                    ->modalDescription('Apakah Anda yakin ingin menghapus user ini? Semua motor dan transaksi yang terkait akan ikut terhapus. User dengan transaksi aktif tidak dapat dihapus.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                $activeTransactions = \App\Models\Transaction::whereHas('motorcycle', function ($query) use ($record) {
                                    $query->where('owner_id', $record->id);
                                })->whereIn('rental_status', ['pending', 'on_going'])->exists();

                                if ($activeTransactions) {
                                    throw new \Exception("Tidak dapat menghapus user '{$record->name}' karena masih memiliki transaksi aktif. Selesaikan atau batalkan transaksi terlebih dahulu.");
                                }
                            }

                            foreach ($records as $record) {
                                \App\Models\Transaction::whereHas('motorcycle', function ($query) use ($record) {
                                    $query->where('owner_id', $record->id);
                                })->delete();
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Users')
                        ->modalDescription('Apakah Anda yakin ingin menghapus users yang dipilih? Semua motor dan transaksi yang terkait akan ikut terhapus. Users dengan transaksi aktif tidak dapat dihapus.')
                        ->modalSubmitActionLabel('Ya, Hapus'),
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
