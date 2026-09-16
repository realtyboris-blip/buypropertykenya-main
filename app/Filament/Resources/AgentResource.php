<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Models\Agent;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Facades\Hash;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Real Estate';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Account')
                    ->description('Create or select a user account for this agent')
                    ->schema([
                        Select::make('user_id')
                            ->label('Existing User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Select an existing user or create a new one below'),
                        
                        Forms\Components\Fieldset::make('New User')
                            ->schema([
                                TextInput::make('new_user_name')
                                    ->label('Name')
                                    ->maxLength(255),
                                TextInput::make('new_user_email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('new_user_password')
                                    ->label('Password')
                                    ->password()
                                    ->maxLength(255),
                            ])
                            ->columns(3),
                    ]),
                
                Section::make('Agent Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('license_number')
                                    ->label('License Number')
                                    ->maxLength(255),
                                
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                
                                TextInput::make('specialization')
                                    ->maxLength(255),
                                
                                FileUpload::make('photo')
                                    ->image()
                                    ->directory('agents')
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('100px'),
                                
                                Toggle::make('is_featured')
                                    ->label('Featured Agent')
                                    ->default(false),
                                
                                TextInput::make('rating')
                                    ->numeric()
                                    ->step(0.1)
                                    ->minValue(0)
                                    ->maxValue(5)
                                    ->default(0),
                                
                                TextInput::make('properties_sold')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        
                        Textarea::make('bio')
                            ->rows(5)
                            ->maxLength(65535),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/placeholder-agent.jpg'),
                
                TextColumn::make('user.name')
                    ->label('Agent Name')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                
                TextColumn::make('phone')
                    ->searchable(),
                
                TextColumn::make('specialization')
                    ->searchable(),
                
                TextColumn::make('rating')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => '⭐ ' . $state . '/5'),
                
                TextColumn::make('properties_sold')
                    ->sortable(),
                
                IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('specialization'),
                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Only')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}