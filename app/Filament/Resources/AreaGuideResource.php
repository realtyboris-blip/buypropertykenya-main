<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaGuideResource\Pages;
use App\Models\AreaGuide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Str;

class AreaGuideResource extends Resource
{
    protected static ?string $model = AreaGuide::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationGroup = 'Media';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Area Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                
                                Textarea::make('description')
                                    ->rows(4)
                                    ->columnSpan(2),
                                
                                FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->image()
                                    ->directory('area-guides')
                                    ->maxSize(5120)
                                    ->imagePreviewHeight('150'),
                                
                                // Video Section
                                Section::make('Video Tour (Optional)')
                                    ->schema([
                                        Select::make('video_type')
                                            ->label('Video Source')
                                            ->options([
                                                'youtube' => 'YouTube',
                                                'vimeo' => 'Vimeo',
                                                'local' => 'Upload Video',
                                            ])
                                            ->placeholder('Select video source')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                if ($state !== 'local') {
                                                    $set('video_url', null);
                                                }
                                            }),
                                        
                                        TextInput::make('video_url')
                                            ->label(function (callable $get) {
                                                $type = $get('video_type');
                                                return match ($type) {
                                                    'youtube' => 'YouTube URL',
                                                    'vimeo' => 'Vimeo URL',
                                                    default => 'Video URL',
                                                };
                                            })
                                            ->placeholder(function (callable $get) {
                                                $type = $get('video_type');
                                                return match ($type) {
                                                    'youtube' => 'https://www.youtube.com/watch?v=...',
                                                    'vimeo' => 'https://vimeo.com/...',
                                                    default => 'Enter video URL',
                                                };
                                            })
                                            ->visible(fn (callable $get) => $get('video_type') && $get('video_type') !== 'local')
                                            ->url()
                                            ->helperText(function (callable $get) {
                                                $type = $get('video_type');
                                                if ($type === 'youtube') {
                                                    return 'Supports standard YouTube URLs, shortened URLs, and embed URLs';
                                                } elseif ($type === 'vimeo') {
                                                    return 'Supports standard Vimeo URLs and embed URLs';
                                                }
                                                return null;
                                            }),
                                        
                                        FileUpload::make('video_url')
                                            ->label('Upload Video')
                                            ->directory('videos/area-guides')
                                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'])
                                            ->maxSize(102400) // 100MB in KB
                                            ->helperText('MP4, MOV, AVI, WebM supported. Max 100MB.')
                                            ->columnSpan(2)
                                            ->visible(fn (callable $get) => $get('video_type') === 'local')
                                            ->uploadingMessage('Uploading video...'),
                                        
                                        TextInput::make('video_title')
                                            ->label('Video Title')
                                            ->placeholder('e.g., Tour of the Neighborhood')
                                            ->maxLength(255)
                                            ->helperText('Optional title displayed below the video')
                                            ->columnSpan(2),
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->columnSpan(2),
                                
                                FileUpload::make('gallery_images')
                                    ->label('Gallery Images')
                                    ->multiple()
                                    ->image()
                                    ->directory('area-guides/gallery')
                                    ->maxSize(5120)
                                    ->imagePreviewHeight('100')
                                    ->reorderable()
                                    ->helperText('Upload multiple images for the gallery')
                                    ->columnSpan(2),
                                
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                                
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ]),
                
                Section::make('Amenities')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Repeater::make('amenities_schools')
                                    ->label('Schools')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('School Name')
                                            ->required(),
                                    ])
                                    ->defaultItems(0)
                                    ->collapsible(),
                                
                                Repeater::make('amenities_hospitals')
                                    ->label('Hospitals')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Hospital Name')
                                            ->required(),
                                    ])
                                    ->defaultItems(0)
                                    ->collapsible(),
                                
                                Repeater::make('amenities_sports')
                                    ->label('Sports Facilities')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Facility Name')
                                            ->required(),
                                    ])
                                    ->defaultItems(0)
                                    ->collapsible(),
                                
                                Repeater::make('amenities_shopping')
                                    ->label('Shopping Centers')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Center Name')
                                            ->required(),
                                    ])
                                    ->defaultItems(0)
                                    ->collapsible(),
                            ]),
                    ]),
                
                Section::make('Nearby Places')
                    ->schema([
                        Repeater::make('nearby_places')
                            ->schema([
                                TextInput::make('place')
                                    ->label('Place Name')
                                    ->required(),
                            ])
                            ->defaultItems(0)
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->circular()
                    ->width(50)
                    ->height(50)
                    ->defaultImageUrl('/images/placeholder.jpg'),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                IconColumn::make('has_video')
                    ->label('Video')
                    ->boolean()
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-x-mark')
                    ->getStateUsing(fn ($record) => $record->hasVideo()),
                
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('sort_order')
                    ->sortable(),
                
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\Filter::make('has_video')
                    ->label('Has Video')
                    ->toggle()
                    ->query(fn ($query) => $query->whereNotNull('video_url')),
                
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreaGuides::route('/'),
            'create' => Pages\CreateAreaGuide::route('/create'),
            'edit' => Pages\EditAreaGuide::route('/{record}/edit'),
        ];
    }
}