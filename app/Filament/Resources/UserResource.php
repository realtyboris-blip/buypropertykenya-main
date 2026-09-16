<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Administration';
    
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        TextInput::make('password')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                        
                        Toggle::make('is_admin')
                            ->label('Administrator')
                            ->helperText('Administrators have full access'),
                        
                        Toggle::make('email_verified')
                            ->label('Email Verified')
                            ->default(false)
                            ->helperText('Mark as verified if email is confirmed'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-m-user'),
                
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->copyMessage('Email copied'),
                
                BadgeColumn::make('role')
                    ->label('Role')
                    ->getStateUsing(fn ($record): string => $record->getRoleAttribute())
                    ->colors([
                        'danger' => 'Admin',
                        'warning' => 'Agent',
                        'success' => 'User',
                    ])
                    ->icons([
                        'heroicon-o-shield-check' => 'Admin',
                        'heroicon-o-user-group' => 'Agent',
                        'heroicon-o-user' => 'User',
                    ]),
                
                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record): bool => !is_null($record->email_verified_at)),
                
                TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('M d, Y')
                    ->sortable(),
                
                TextColumn::make('agent.license_number')
                    ->label('License #')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Administrators',
                        'agent' => 'Agents',
                        'user' => 'Regular Users',
                    ])
                    ->query(function ($query, array $data) {
                        if ($data['value'] === 'admin') {
                            return $query->where('is_admin', true);
                        }
                        if ($data['value'] === 'agent') {
                            return $query->whereHas('agent');
                        }
                        if ($data['value'] === 'user') {
                            return $query->where('is_admin', false)->whereDoesntHave('agent');
                        }
                        return $query;
                    }),
                
                Tables\Filters\Filter::make('verified')
                    ->label('Email Verified')
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at')),
                
                Tables\Filters\Filter::make('unverified')
                    ->label('Unverified')
                    ->query(fn ($query) => $query->whereNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verify Email')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => is_null($record->email_verified_at))
                    ->action(function ($record) {
                        $record->update(['email_verified_at' => now()]);
                        \Filament\Notifications\Notification::make()
                            ->title('User verified successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('verifySelected')
                    ->label('Verify Selected')
                    ->icon('heroicon-o-check-circle')
                    ->action(function ($records) {
                        $records->each->update(['email_verified_at' => now()]);
                        \Filament\Notifications\Notification::make()
                            ->title('Users verified successfully')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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