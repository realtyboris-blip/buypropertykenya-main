<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PodcastResource\Pages;
use App\Models\Podcast;
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

class PodcastResource extends Resource
{
    protected static ?string $model = Podcast::class;

    protected static ?string $navigationIcon = 'heroicon-o-microphone';

    protected static ?string $navigationGroup = 'Media';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Podcast Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Select::make('media_type')
                                    ->options([
                                        'audio' => 'Audio',
                                        'video' => 'Video',
                                    ])
                                    ->required()
                                    ->default('audio')
                                    ->reactive()
                                    ->afterStateUpdated(fn($state) => $state),

                                Select::make('platform')
                                    ->options([
                                        'youtube' => 'YouTube',
                                        'spotify' => 'Spotify',
                                        'apple' => 'Apple Podcasts',
                                        'soundcloud' => 'SoundCloud',
                                        'google' => 'Google Podcasts',
                                        'other' => 'Other',
                                    ])
                                    ->placeholder('Select platform'),

                                TextInput::make('host')
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->rows(4),

                                // Audio URL (for audio podcasts)
                                TextInput::make('audio_url')
                                    ->label('Audio URL')
                                    ->url()
                                    ->placeholder('https://...')
                                    ->visible(fn($get) => $get('media_type') === 'audio'),

                                // Video URL (for video podcasts)
                                TextInput::make('video_url')
                                    ->label('Video URL')
                                    ->url()
                                    ->placeholder('https://www.youtube.com/watch?v=...')
                                    ->visible(fn($get) => $get('media_type') === 'video'),

                                TextInput::make('embed_url')
                                    ->label('Embed URL')
                                    ->url()
                                    ->placeholder('https://www.youtube.com/embed/...')
                                    ->helperText('Optional: Direct embed URL for video platforms'),

                                TextInput::make('duration')
                                    ->label('Duration')
                                    ->placeholder('e.g., 25:30'),

                                FileUpload::make('cover_image')
                                    ->label('Cover Image')
                                    ->image()
                                    ->directory('podcasts')
                                    ->maxSize(5120) // 5MB max
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('800')
                                    ->imageResizeTargetHeight('800')
                                    ->imagePreviewHeight('150')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                                    ->helperText('Recommended size: 800x800px. Max file size: 5MB. Supported formats: JPG, PNG, WEBP'),

                                FileUpload::make('video_thumbnail')
                                    ->label('Video Thumbnail')
                                    ->image()
                                    ->directory('podcasts')
                                    ->maxSize(5120)
                                    ->imagePreviewHeight('100')
                                    ->visible(fn($get) => $get('media_type') === 'video'),

                                TextInput::make('tags')
                                    ->label('Tags (comma separated)')
                                    ->placeholder('real estate, property, investment')
                                    ->helperText('Separate tags with commas'),
                            ]),
                    ]),

                Section::make('Publishing Options')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_featured')
                                    ->label('Featured Episode')
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
                ImageColumn::make('cover_image')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/podcast-placeholder.jpg'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                BadgeColumn::make('media_type')
                    ->colors([
                        'success' => 'audio',
                        'info' => 'video',
                    ])
                    ->icons([
                        'heroicon-o-microphone' => 'audio',
                        'heroicon-o-video-camera' => 'video',
                    ]),

                TextColumn::make('host')
                    ->searchable(),

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

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('media_type')
                    ->options([
                        'audio' => 'Audio',
                        'video' => 'Video',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Only')
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => route('podcasts.show', $record->slug))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListPodcasts::route('/'),
            'create' => Pages\CreatePodcast::route('/create'),
            'edit' => Pages\EditPodcast::route('/{record}/edit'),
        ];
    }
}
