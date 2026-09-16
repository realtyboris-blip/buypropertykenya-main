<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use App\Models\BlogTag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Support\Str;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Auto-assign current user as author - using closure
                Hidden::make('user_id')
                    ->default(fn() => auth()->id())
                    ->required(),

                Tabs::make('Blog')
                    ->tabs([
                        Tab::make('Content')
                            ->icon('heroicon-o-pencil')
                            ->schema([
                                Section::make('Blog Content')
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->placeholder('Enter blog title...'),

                                        TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->helperText('URL-friendly version of the title (auto-generated)'),

                                        Grid::make(2)
                                            ->schema([
                                                Select::make('blog_category_id')
                                                    ->label('Category')
                                                    ->relationship('category', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('Select a category'),

                                                Select::make('tags')
                                                    ->label('Tags')
                                                    ->relationship('tags', 'name')
                                                    ->multiple()
                                                    ->searchable()
                                                    ->preload()
                                                    ->placeholder('Select or create tags')
                                                    ->createOptionForm([
                                                        TextInput::make('name')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->live(onBlur: true)
                                                            ->afterStateUpdated(function ($state, callable $set) {
                                                                $set('slug', Str::slug($state));
                                                            }),
                                                        // Replace the slug field with this:
                                                        TextInput::make('slug')
                                                            ->required()
                                                            ->unique(ignoreRecord: true)
                                                            ->helperText('URL-friendly version of the title (auto-generated)')
                                                            ->dehydrated(true)
                                                            ->afterStateHydrated(function ($component, $state, $record) {
                                                                // If slug is empty, generate from title
                                                                if (empty($state) && $record) {
                                                                    $component->state($record->title ? Str::slug($record->title) : '');
                                                                }
                                                            }),
                                                    ])
                                                    ->createOptionUsing(function (array $data): int {
                                                        if (empty($data['name'])) {
                                                            throw new \Exception('Tag name is required');
                                                        }

                                                        if (empty($data['slug'])) {
                                                            $data['slug'] = Str::slug($data['name']);
                                                        }

                                                        $existingTag = BlogTag::where('slug', $data['slug'])->first();
                                                        if ($existingTag) {
                                                            return $existingTag->getKey();
                                                        }

                                                        $tag = BlogTag::create([
                                                            'name' => $data['name'],
                                                            'slug' => $data['slug'],
                                                        ]);

                                                        return $tag->getKey();
                                                    }),
                                            ]),

                                        TextInput::make('excerpt')
                                            ->label('Excerpt (Short Description)')
                                            ->maxLength(500)
                                            ->helperText('A brief summary of the blog post (max 500 characters)')
                                            ->placeholder('Write a short summary...'),

                                        RichEditor::make('content')
                                            ->required()
                                            ->fileAttachmentsDirectory('blog')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'underline',
                                                'strike',
                                                'link',
                                                'image',
                                                'blockquote',
                                                'bulletList',
                                                'orderedList',
                                                'h2',
                                                'h3',
                                                'h4',
                                                'alignLeft',
                                                'alignCenter',
                                                'alignRight',
                                            ])
                                            ->placeholder('Write your blog content here...')
                                            ->columnSpanFull(),

                                        FileUpload::make('featured_image')
                                            ->label('Featured Image')
                                            ->image()
                                            ->directory('blog/featured')
                                            ->maxSize(5120)
                                            ->imageResizeMode('cover')
                                            ->imageResizeTargetWidth('800')
                                            ->imageResizeTargetHeight('420')
                                            ->imagePreviewHeight('150')
                                            ->helperText('Recommended size: 800x420px. Max file size: 5MB')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                Section::make('Meta Tags')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(70)
                                            ->helperText('Recommended: 50-70 characters. Leave blank to use blog title.'),

                                        Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Recommended: 150-160 characters. Leave blank to use excerpt.'),

                                        TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->placeholder('real estate, property, Kenya, homes')
                                            ->helperText('Comma-separated keywords'),

                                        TextInput::make('canonical_url')
                                            ->label('Canonical URL')
                                            ->url()
                                            ->helperText('Leave empty to use the default URL'),
                                    ]),

                                Section::make('Open Graph (Social Sharing)')
                                    ->schema([
                                        TextInput::make('og_title')
                                            ->label('OG Title')
                                            ->maxLength(70)
                                            ->helperText('Leave blank to use meta title'),

                                        Textarea::make('og_description')
                                            ->label('OG Description')
                                            ->rows(3)
                                            ->maxLength(160)
                                            ->helperText('Leave blank to use meta description'),

                                        FileUpload::make('og_image')
                                            ->label('OG Image')
                                            ->image()
                                            ->directory('blog/og')
                                            ->maxSize(5120)
                                            ->helperText('Recommended: 1200x630px. Leave blank to use featured image.'),
                                    ]),

                                Section::make('Twitter Card')
                                    ->schema([
                                        Select::make('twitter_card')
                                            ->options([
                                                'summary' => 'Summary',
                                                'summary_large_image' => 'Summary with Large Image',
                                                'app' => 'App',
                                                'player' => 'Player',
                                            ])
                                            ->default('summary_large_image'),

                                        TextInput::make('twitter_title')
                                            ->label('Twitter Title')
                                            ->maxLength(70),

                                        Textarea::make('twitter_description')
                                            ->label('Twitter Description')
                                            ->rows(3)
                                            ->maxLength(160),

                                        FileUpload::make('twitter_image')
                                            ->label('Twitter Image')
                                            ->image()
                                            ->directory('blog/twitter')
                                            ->maxSize(5120)
                                            ->helperText('Recommended: 1200x675px'),
                                    ]),

                                Section::make('Schema Markup')
                                    ->schema([
                                        Select::make('schema_type')
                                            ->options([
                                                'Article' => 'Article',
                                                'BlogPosting' => 'Blog Posting',
                                                'NewsArticle' => 'News Article',
                                                'TechArticle' => 'Tech Article',
                                            ])
                                            ->default('Article'),
                                    ]),
                            ]),

                        Tab::make('Publishing')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Section::make('Publishing Options')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Select::make('status')
                                                    ->options([
                                                        'draft' => 'Draft',
                                                        'published' => 'Published',
                                                        'archived' => 'Archived',
                                                    ])
                                                    ->required()
                                                    ->default('draft'),

                                                DateTimePicker::make('published_at')
                                                    ->label('Publish Date/Time')
                                                    ->default(now()),

                                                Toggle::make('is_featured')
                                                    ->label('Featured Post')
                                                    ->helperText('Featured posts appear prominently on the blog page'),
                                            ]),
                                    ]),
                            ]),
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

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('bold'),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'published',
                        'warning' => 'draft',
                        'danger' => 'archived',
                    ])
                    ->sortable(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->alignEnd(),

                IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable()
                    ->label('Featured'),

                TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
                        'archived' => 'Archived',
                    ]),

                Tables\Filters\SelectFilter::make('blog_category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Tables\Filters\Filter::make('is_featured')
                    ->label('Featured Only')
                    ->toggle(),

                Tables\Filters\Filter::make('published_at')
                    ->form([
                        Forms\Components\DatePicker::make('published_from')
                            ->label('Published From'),
                        Forms\Components\DatePicker::make('published_until')
                            ->label('Published Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['published_from'], fn($q) => $q->whereDate('published_at', '>=', $data['published_from']))
                            ->when($data['published_until'], fn($q) => $q->whereDate('published_at', '<=', $data['published_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn($record) => url('/blog/' . $record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->status === 'draft')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Blog published successfully!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('unpublish')
                    ->label('Unpublish')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn($record) => $record->status === 'published')
                    ->action(function ($record) {
                        $record->update(['status' => 'draft']);
                        \Filament\Notifications\Notification::make()
                            ->title('Blog unpublished')
                            ->warning()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('publishSelected')
                    ->label('Publish Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update([
                                'status' => 'published',
                                'published_at' => now(),
                            ]);
                        });
                        \Filament\Notifications\Notification::make()
                            ->title($records->count() . ' blogs published!')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\BulkAction::make('unpublishSelected')
                    ->label('Unpublish Selected')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->action(function ($records) {
                        $records->each->update(['status' => 'draft']);
                        \Filament\Notifications\Notification::make()
                            ->title($records->count() . ' blogs unpublished')
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'draft')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
