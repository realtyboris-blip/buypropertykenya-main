<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListingTypeResource\Pages;
use App\Models\ListingType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;

class ListingTypeResource extends Resource
{
    protected static ?string $model = ListingType::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Real Estate';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Listing Type Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state))),
                                
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                
                                Select::make('color')
                                    ->label('Badge Color')
                                    ->options([
                                        'success' => 'Green (Success)',
                                        'danger' => 'Red (Danger)',
                                        'warning' => 'Yellow (Warning)',
                                        'info' => 'Blue (Info)',
                                        'gray' => 'Gray (Default)',
                                        'primary' => 'Primary',
                                    ])
                                    ->default('gray')
                                    ->helperText('Color for status badges'),
                                
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first'),
                                
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                        
                        // Emoji Icon (simple option)
                        TextInput::make('icon')
                            ->label('Emoji Icon (optional)')
                            ->maxLength(10)
                            ->placeholder('💰 🏠 📋')
                            ->helperText('Use emoji for quick icon (e.g., 💰 for sale)'),
                        
                        // SVG Icon Upload
                        FileUpload::make('icon_svg')
                            ->label('SVG Icon')
                            ->acceptedFileTypes(['image/svg+xml'])
                            ->directory('listing-icons')
                            ->maxSize(1024) // 1MB max for SVG
                            ->helperText('Upload SVG file for custom icon (recommended)')
                            ->image()
                            ->imagePreviewHeight('100')
                            ->loadingIndicatorPosition('left')
                            ->columnSpanFull(),
                        
                        // SVG Code Editor (advanced)
                        Forms\Components\Textarea::make('icon_svg_code')
                            ->label('Or Paste SVG Code')
                            ->rows(5)
                            ->helperText('Paste raw SVG code here as an alternative to uploading')
                            ->afterStateHydrated(function ($component, $state, $record) {
                                if ($record && $record->icon_svg) {
                                    $component->state($record->icon_svg);
                                }
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('icon_svg', $state);
                                }
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('icon')
                    ->label('Icon')
                    ->formatStateUsing(fn ($state, $record) => $record->icon_svg ? 'SVG' : ($state ?? '—'))
                    ->badge()
                    ->color('info'),
                
                BadgeColumn::make('color')
                    ->label('Badge Color')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->colors([
                        'success' => 'success',
                        'danger' => 'danger',
                        'warning' => 'warning',
                        'info' => 'info',
                        'primary' => 'primary',
                        'gray' => 'gray',
                    ]),
                
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),
                
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('properties_count')
                    ->counts('properties')
                    ->label('Properties')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\Filter::make('is_active')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListingTypes::route('/'),
            'create' => Pages\CreateListingType::route('/create'),
            'edit' => Pages\EditListingType::route('/{record}/edit'),
        ];
    }
}