<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\ListingType;
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
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Real Estate';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', \Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Select::make('property_type_id')
                                    ->label('Property Type')
                                    ->options(PropertyType::where('is_active', true)->orderBy('sort_order')->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->helperText('Select from existing or create new type below')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', \Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique('property_types', 'slug'),
                                        TextInput::make('icon')
                                            ->maxLength(255)
                                            ->placeholder('🏠 🏢 🏭')
                                            ->helperText('Optional emoji icon'),
                                        FileUpload::make('icon_svg')
                                            ->label('SVG Icon')
                                            ->acceptedFileTypes(['image/svg+xml'])
                                            ->directory('property-icons')
                                            ->maxSize(1024)
                                            ->helperText('Or upload an SVG icon (recommended)')
                                            ->image()
                                            ->imagePreviewHeight('50'),
                                        Toggle::make('is_active')->default(true),
                                        TextInput::make('sort_order')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        // Handle SVG file upload if present
                                        if (isset($data['icon_svg']) && $data['icon_svg']) {
                                            $svgPath = $data['icon_svg']->store('property-icons', 'public');
                                            $data['icon_svg'] = $svgPath;
                                        }
                                        $type = PropertyType::create($data);
                                        return $type->getKey();
                                    }),

                                Select::make('listing_type_id')
                                    ->label('Listing Type')
                                    ->options(ListingType::where('is_active', true)->orderBy('sort_order')->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->helperText('Select from existing or create new listing type below')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', \Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique('listing_types', 'slug'),
                                        TextInput::make('icon')->maxLength(255),
                                        Toggle::make('is_active')->default(true),
                                        TextInput::make('sort_order')
                                            ->numeric()
                                            ->default(0),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        $type = ListingType::create($data);
                                        return $type->getKey();
                                    }),
                            ]),
                    ]),

                Section::make('Price Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('currency')
                                    ->options([
                                        'KES' => 'Kenyan Shilling (KES)',
                                        'USD' => 'US Dollar (USD)',
                                        'EUR' => 'Euro (EUR)',
                                        'GBP' => 'British Pound (GBP)',
                                    ])
                                    ->default('KES')
                                    ->required()
                                    ->reactive(),

                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix(fn($get) => $get('currency') ?: 'KES')
                                    ->helperText('Enter price in selected currency'),
                            ]),
                    ]),

                Section::make('Property Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('bedrooms')->numeric()->minValue(0),
                                TextInput::make('bathrooms')->numeric()->minValue(0),
                                TextInput::make('area_sqft')->numeric()->minValue(0)->suffix('sq ft'),
                            ]),

                        Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),

                Section::make('Location')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('address')->required(),
                                TextInput::make('city')->required(),
                                TextInput::make('state'),
                                TextInput::make('zipcode'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')->numeric()->step(0.000001),
                                TextInput::make('longitude')->numeric()->step(0.000001),
                            ]),
                    ]),

                Section::make('Property Images')
                    ->description('Upload multiple images for this property')
                    ->schema([
                        FileUpload::make('images')
                            ->label('Property Images')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->directory('properties/' . date('Y/m/d'))
                            ->maxFiles(10)
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                            ->reorderable()
                            ->appendFiles()
                            ->preserveFilenames()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Section::make('Property Video Tour')
                    ->description('Add a video tour URL (YouTube, Vimeo, etc.)')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('video_url')
                                    ->label('Video Tour URL')
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->helperText('Paste YouTube, Vimeo, or other video platform URL')
                                    ->url()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                        }
                                    }),

                                FileUpload::make('video_thumbnail')
                                    ->label('Video Thumbnail (Optional)')
                                    ->image()
                                    ->directory('properties/videos')
                                    ->maxSize(5120)
                                    ->helperText('Upload a custom thumbnail for the video'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Amenities')
                    ->schema([
                        Repeater::make('amenities')
                            ->schema([
                                TextInput::make('amenity')
                                    ->required()
                                    ->placeholder('e.g., Swimming Pool, Gym, Parking'),
                            ])
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ]),

                Section::make('Property Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured Property')
                                    ->default(false),

                                Toggle::make('is_verified')
                                    ->label('Verified Property')
                                    ->default(false),

                                Select::make('status')
                                    ->options([
                                        'available' => 'Available',
                                        'sold' => 'Sold',
                                        'rented' => 'Rented',
                                        'pending' => 'Pending',
                                    ])
                                    ->required()
                                    ->default('available'),

                                Select::make('agent_id')
                                    ->label('Agent')
                                    ->relationship('agent', 'id')
                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->user->name ?? 'Unknown')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Image')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/placeholder.jpg'),

                TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('propertyType.name')
                    ->label('Type')
                    ->formatStateUsing(function ($state, $record) {
                        $type = $record->propertyType;
                        if ($type) {
                            if ($type->icon_svg) {
                                // For SVG stored as path
                                $svgContent = file_exists(storage_path('app/public/' . $type->icon_svg))
                                    ? file_get_contents(storage_path('app/public/' . $type->icon_svg))
                                    : $type->icon_svg;
                                if ($svgContent && preg_match('/<svg[^>]*>/', $svgContent)) {
                                    return '<div class="flex items-center gap-2"><div class="w-5 h-5">' . $svgContent . '</div><span>' . e($type->name) . '</span></div>';
                                }
                            }
                            if ($type->icon) {
                                return $type->icon . ' ' . $type->name;
                            }
                            return $type->name;
                        }
                        return $state ?? '—';
                    })
                    ->html()
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('propertyType', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->sortable(),
                // Update the listing type column in the table method
                TextColumn::make('listingType.name')
                    ->label('Listing')
                    ->formatStateUsing(function ($state, $record) {
                        $type = $record->listingType;
                        if ($type) {
                            if ($type->icon_svg) {
                                // Check if it's stored as path or actual SVG
                                $svgContent = $type->icon_svg;
                                if (file_exists(storage_path('app/public/' . $type->icon_svg))) {
                                    $svgContent = file_get_contents(storage_path('app/public/' . $type->icon_svg));
                                }
                                if ($svgContent && preg_match('/<svg[^>]*>/', $svgContent)) {
                                    return '<div class="flex items-center gap-2"><div class="w-5 h-5">' . $svgContent . '</div><span>' . e($type->name) . '</span></div>';
                                }
                            }
                            if ($type->icon) {
                                return $type->icon . ' ' . $type->name;
                            }
                            return $type->name;
                        }
                        return $state ?? '—';
                    })
                    ->html()
                    ->badge()
                    ->color(fn($record) => $record->listingType?->color ?? 'gray')
                    ->sortable(),

                TextColumn::make('price')
                    ->formatStateUsing(fn($state, $record) => self::formatPrice($state, $record->currency ?? 'KES'))
                    ->sortable(),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bedrooms')
                    ->label('Beds')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('bathrooms')
                    ->label('Baths')
                    ->sortable()
                    ->toggleable(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'danger' => 'sold',
                        'warning' => 'pending',
                        'info' => 'rented',
                    ])
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('listing_type_id')
                    ->label('Listing Type')
                    ->relationship('listingType', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('property_type_id')
                    ->label('Property Type')
                    ->relationship('propertyType', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'sold' => 'Sold',
                        'rented' => 'Rented',
                        'pending' => 'Pending',
                    ]),

                Tables\Filters\SelectFilter::make('city')
                    ->options(fn() => Property::pluck('city', 'city')->toArray())
                    ->searchable(),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Only')
                    ->toggle(),

                Tables\Filters\Filter::make('price_range')
                    ->form([
                        TextInput::make('min_price')->numeric()->placeholder('Min'),
                        TextInput::make('max_price')->numeric()->placeholder('Max'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min_price'], fn($q) => $q->where('price', '>=', $data['min_price']))
                            ->when($data['max_price'], fn($q) => $q->where('price', '<=', $data['max_price']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('feature')
                    ->label('Feature Selected')
                    ->icon('heroicon-o-star')
                    ->action(fn($records) => $records->each->update(['is_featured' => true])),
                Tables\Actions\BulkAction::make('unfeature')
                    ->label('Unfeature Selected')
                    ->icon('heroicon-o-star')
                    ->action(fn($records) => $records->each->update(['is_featured' => false])),
            ]);
    }

    protected static function formatPrice($price, $currency): string
    {
        $symbols = [
            'KES' => 'KSh ',
            'USD' => '$ ',
            'EUR' => '€ ',
            'GBP' => '£ ',
        ];

        $symbol = $symbols[$currency] ?? $currency . ' ';

        return $symbol . number_format($price, 0);
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
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
