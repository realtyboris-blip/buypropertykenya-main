<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Real Estate';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Project Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('URL-friendly version of the project name (auto-generated)'),

                                Select::make('status')
                                    ->options([
                                        'ongoing' => '🔄 Ongoing',
                                        'completed' => '✅ Completed',
                                        'off-plan' => '📋 Off-Plan',
                                    ])
                                    ->required()
                                    ->default('ongoing'),

                                Toggle::make('is_featured')
                                    ->label('Featured Project')
                                    ->default(false)
                                    ->helperText('Featured projects appear prominently on the homepage'),

                                TextInput::make('developer')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('location')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        Textarea::make('description')
                            ->required()
                            ->rows(5)
                            ->helperText('Detailed description of the project'),
                    ]),

                Section::make('Pricing & Units')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('starting_price')
                                    ->label('Starting Price (KES)')
                                    ->numeric()
                                    ->required()
                                    ->prefix('KSh')
                                    ->helperText('Minimum property price in this project'),

                                TextInput::make('max_price')
                                    ->label('Max Price (KES)')
                                    ->numeric()
                                    ->prefix('KSh')
                                    ->helperText('Maximum property price in this project (optional)'),

                                DatePicker::make('completion_date')
                                    ->label('Completion Date')
                                    ->helperText('Expected or actual completion date'),

                                TextInput::make('total_units')
                                    ->label('Total Units')
                                    ->numeric()
                                    ->helperText('Total number of units in the project'),

                                TextInput::make('available_units')
                                    ->label('Available Units')
                                    ->numeric()
                                    ->helperText('Number of units still available for purchase'),
                            ]),
                    ]),

                Section::make('Images & Media')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('cover_image')
                                    ->label('Cover Image')
                                    ->image()
                                    ->directory('projects/covers')
                                    ->maxSize(5120)
                                    ->imagePreviewHeight('200')
                                    ->helperText('Main image for the project (recommended: 1200x800px)'),

                                FileUpload::make('gallery')
                                    ->label('Gallery Images')
                                    ->multiple()
                                    ->image()
                                    ->directory('projects/gallery')
                                    ->maxSize(5120)
                                    ->reorderable()
                                    ->appendFiles()
                                    ->helperText('Upload multiple images for the project gallery'),
                            ]),

                        TextInput::make('video_url')
                            ->label('Video Tour URL')
                            ->url()
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->helperText('YouTube, Vimeo, or other video platform URL'),
                    ]),

                Section::make('Amenities')
                    ->schema([
                        Repeater::make('amenities')
                            ->schema([
                                TextInput::make('amenity')
                                    ->required()
                                    ->placeholder('e.g., Swimming Pool, Gym, Security, Parking'),
                            ])
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->helperText('Add all the amenities available in this project'),
                    ]),

                Section::make('Floor Plans')
                    ->schema([
                        Repeater::make('floor_plans')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->placeholder('e.g., 2-Bedroom, 3-Bedroom Deluxe'),

                                        TextInput::make('area')
                                            ->placeholder('e.g., 120 sqm, 1500 sqft'),

                                        TextInput::make('price')
                                            ->placeholder('e.g., KSh 8,500,000'),

                                        FileUpload::make('image')
                                            ->image()
                                            ->directory('projects/floor-plans')
                                            ->maxSize(5120)
                                            ->helperText('Upload floor plan image'),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->columnSpanFull()
                            ->helperText('Add detailed floor plans for different unit types'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/project-placeholder.jpg'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->weight('bold'),

                TextColumn::make('developer')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                TextColumn::make('city')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'ongoing',
                        'success' => 'completed',
                        'info' => 'off-plan',
                    ])
                    ->formatStateUsing(fn($state) => [
                        'ongoing' => '🔄 Ongoing',
                        'completed' => '✅ Completed',
                        'off-plan' => '📋 Off-Plan',
                    ][$state] ?? $state)
                    ->sortable(),

                TextColumn::make('starting_price')
                    ->label('Starting Price')
                    ->money('KES')
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable()
                    ->label('Featured'),

                TextColumn::make('total_units')
                    ->label('Units')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('is_featured', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ongoing' => '🔄 Ongoing',
                        'completed' => '✅ Completed',
                        'off-plan' => '📋 Off-Plan',
                    ])
                    ->label('Status'),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Only')
                    ->toggle(),

                Tables\Filters\SelectFilter::make('city')
                    ->options(fn() => Project::pluck('city', 'city')->toArray())
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => route('projects.show', $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('featureSelected')
                    ->label('Feature Selected')
                    ->icon('heroicon-o-star')
                    ->action(function ($records) {
                        $records->each->update(['is_featured' => true]);
                        \Filament\Notifications\Notification::make()
                            ->title($records->count() . ' projects featured!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\BulkAction::make('unfeatureSelected')
                    ->label('Unfeature Selected')
                    ->icon('heroicon-o-star')
                    ->color('gray')
                    ->action(function ($records) {
                        $records->each->update(['is_featured' => false]);
                        \Filament\Notifications\Notification::make()
                            ->title($records->count() . ' projects unfeatured')
                            ->warning()
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
