<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Media';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('FAQ Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('question')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('category')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Buying, Selling, Financing'),

                                Textarea::make('answer')
                                    ->required()
                                    ->rows(5)
                                    ->columnSpan(2),

                                // Video Section
                                Section::make('Video (Optional)')
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
                                            ->visible(fn(callable $get) => $get('video_type') && $get('video_type') !== 'local')
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
                                            ->directory('videos/faqs')
                                            ->acceptedFileTypes(['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'])
                                            ->maxSize(102400) // 100MB in KB
                                            ->helperText('MP4, MOV, AVI, WebM supported. Max 100MB.')
                                            ->columnSpan(2)
                                            ->visible(fn(callable $get) => $get('video_type') === 'local')
                                            ->uploadingMessage('Uploading video...'),

                                        TextInput::make('video_title')
                                            ->label('Video Title')
                                            ->placeholder('e.g., How to Buy Your First Home')
                                            ->maxLength(255)
                                            ->helperText('Optional title displayed below the video')
                                            ->columnSpan(2),
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->columnSpan(2),

                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first'),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                IconColumn::make('has_video')
                    ->label('Video')
                    ->boolean()
                    ->trueIcon('heroicon-o-video-camera')
                    ->falseIcon('heroicon-o-x-mark')
                    ->getStateUsing(fn($record) => $record->hasVideo()),

                TextColumn::make('category')
                    ->searchable()
                    ->badge()
                    ->color('info'),

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
                Tables\Filters\SelectFilter::make('category')
                    ->options(fn() => Faq::pluck('category', 'category')->toArray()),

                Tables\Filters\Filter::make('has_video')
                    ->label('Has Video')
                    ->toggle()
                    ->query(fn($query) => $query->whereNotNull('video_url')),

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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
