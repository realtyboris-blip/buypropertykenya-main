<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseTourResource\Pages;
use App\Models\HouseTour;
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
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Str;

class HouseTourResource extends Resource
{
    protected static ?string $model = HouseTour::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Media';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tour Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter tour title...'),

                                Select::make('property_id')
                                    ->label('Related Property (Optional)')
                                    ->relationship('property', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select a property'),

                                Textarea::make('description')
                                    ->rows(4)
                                    ->placeholder('Describe the property tour...'),

                                TextInput::make('video_url')
                                    ->label('Video URL')
                                    ->required()
                                    ->url()
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->helperText('YouTube, Vimeo, or direct video URL')
                                    ->columnSpanFull(),

                                TextInput::make('duration')
                                    ->label('Duration (seconds)')
                                    ->numeric()
                                    ->placeholder('e.g., 180 for 3 minutes')
                                    ->helperText('Optional'),

                                FileUpload::make('thumbnail')
                                    ->label('Thumbnail Image (Optional)')
                                    ->image()
                                    ->directory('house-tours')
                                    ->maxSize(5120)
                                    ->imagePreviewHeight('100')
                                    ->helperText('Optional - auto-generated from YouTube if not provided'),
                            ]),
                    ]),

                Section::make('Publishing Options')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured Tour')
                                    ->default(false),

                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                    ])
                                    ->required()
                                    ->default('draft'),

                                DateTimePicker::make('published_at')
                                    ->label('Publish Date/Time')
                                    ->default(now()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/video-placeholder.jpg'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('property.title')
                    ->label('Property')
                    ->searchable()
                    ->sortable()
                    ->limit(20),

                TextColumn::make('video_url')
                    ->label('Video')
                    ->limit(20)
                    ->copyable()
                    ->copyMessage('URL copied!'),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                    ])
                    ->sortable(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Only')
                    ->toggle(),

                Tables\Filters\Filter::make('has_video')
                    ->label('Has Video')
                    ->query(fn($query) => $query->whereNotNull('video_url')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('watch')
                    ->label('Watch')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->url(fn($record) => $record->video_url ?? '#')
                    ->openUrlInNewTab()
                    ->visible(fn($record) => !empty($record->video_url)),
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
            'index' => Pages\ListHouseTours::route('/'),
            'create' => Pages\CreateHouseTour::route('/create'),
            'edit' => Pages\EditHouseTour::route('/{record}/edit'),
        ];
    }
}
