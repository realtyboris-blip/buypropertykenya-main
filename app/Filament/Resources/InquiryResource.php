<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Notifications\Notification;
use App\Mail\InquiryReply;
use Illuminate\Support\Facades\Mail;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationGroup = 'Real Estate';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Inquiry Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                
                                Select::make('inquiry_type')
                                    ->label('Inquiry Type')
                                    ->options([
                                        'viewing' => 'Property Viewing',
                                        'consultation' => 'Consultation',
                                        'mortgage' => 'Mortgage Information',
                                        'valuation' => 'Property Valuation',
                                        'general' => 'General Inquiry',
                                        'complaint' => 'Complaint',
                                    ])
                                    ->required(),
                                
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'contacted' => 'Contacted',
                                        'completed' => 'Completed',
                                        'spam' => 'Spam',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required(),
                                
                                DateTimePicker::make('preferred_date')
                                    ->label('Preferred Date')
                                    ->displayFormat('d/m/Y H:i')
                                    ->placeholder('Select preferred date and time'),
                                
                                TextInput::make('preferred_time')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Morning, Afternoon, 10:00 AM'),
                                
                                Select::make('budget')
                                    ->label('Budget Range')
                                    ->options([
                                        '1-5M' => 'KSh 1M - 5M',
                                        '5-10M' => 'KSh 5M - 10M',
                                        '10-20M' => 'KSh 10M - 20M',
                                        '20-50M' => 'KSh 20M - 50M',
                                        '50M+' => 'KSh 50M+',
                                    ])
                                    ->placeholder('Select budget range'),
                                
                                Select::make('contact_method')
                                    ->label('Preferred Contact Method')
                                    ->options([
                                        'phone' => '📞 Phone Call',
                                        'whatsapp' => '💬 WhatsApp',
                                        'email' => '✉️ Email',
                                    ])
                                    ->default('phone'),
                            ]),
                        
                        Textarea::make('message')
                            ->required()
                            ->rows(4),
                        
                        // Reply section for staff
                        Forms\Components\RichEditor::make('staff_reply')
                            ->label('Staff Reply')
                            ->placeholder('Add your reply here...')
                            ->helperText('This reply will be visible to the customer')
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Property Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('property_id')
                                    ->label('Related Property')
                                    ->relationship('property', 'title')
                                    ->searchable()
                                    ->preload(),
                                
                                Select::make('project_id')
                                    ->label('Related Project')
                                    ->relationship('project', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                    ])
                    ->collapsible(),
                
                Section::make('Additional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('source')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->default('website'),
                                
                                TextInput::make('session_id')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->visible(fn ($record) => $record?->session_id),
                                
                                TextInput::make('ip_address')
                                    ->maxLength(255)
                                    ->disabled()
                                    ->visible(fn ($record) => $record?->ip_address),
                                
                                DateTimePicker::make('created_at')
                                    ->label('Submitted At')
                                    ->disabled()
                                    ->visible(fn ($record) => $record?->created_at),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
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
                
                TextColumn::make('phone')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Phone copied'),
                
                BadgeColumn::make('inquiry_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'viewing',
                        'warning' => 'consultation',
                        'success' => 'mortgage',
                        'info' => 'valuation',
                        'gray' => 'general',
                        'danger' => 'complaint',
                    ])
                    ->icons([
                        'heroicon-o-eye' => 'viewing',
                        'heroicon-o-chat-bubble-left-right' => 'consultation',
                        'heroicon-o-currency-dollar' => 'mortgage',
                        'heroicon-o-chart-bar' => 'valuation',
                        'heroicon-o-information-circle' => 'general',
                        'heroicon-o-exclamation-triangle' => 'complaint',
                    ])
                    ->sortable(),
                
                TextColumn::make('budget')
                    ->label('Budget')
                    ->getStateUsing(fn ($record) => $record->budget_label)
                    ->toggleable(),
                
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'contacted',
                        'success' => 'completed',
                        'danger' => 'spam',
                        'gray' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-phone' => 'contacted',
                        'heroicon-o-check-circle' => 'completed',
                        'heroicon-o-x-circle' => 'spam',
                        'heroicon-o-ban' => 'cancelled',
                    ])
                    ->sortable(),
                
                TextColumn::make('source')
                    ->label('Source')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                
                TextColumn::make('property.title')
                    ->label('Property')
                    ->searchable()
                    ->toggleable(),
                
                TextColumn::make('preferred_date')
                    ->label('Preferred Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(),
                
                IconColumn::make('has_reply')
                    ->label('Replied')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record): bool => !is_null($record->staff_reply))
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'contacted' => 'Contacted',
                        'completed' => 'Completed',
                        'spam' => 'Spam',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Status'),
                
                Tables\Filters\SelectFilter::make('inquiry_type')
                    ->options([
                        'viewing' => 'Property Viewing',
                        'consultation' => 'Consultation',
                        'mortgage' => 'Mortgage Information',
                        'valuation' => 'Property Valuation',
                        'general' => 'General Inquiry',
                        'complaint' => 'Complaint',
                    ])
                    ->label('Inquiry Type'),
                
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'website' => 'Website',
                        'website_lead_form' => 'Lead Form',
                        'phone' => 'Phone',
                        'email' => 'Email',
                        'walk_in' => 'Walk-in',
                    ])
                    ->label('Source'),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
                
                Tables\Filters\Filter::make('has_reply')
                    ->label('Has Reply')
                    ->toggle()
                    ->query(fn ($query) => $query->whereNotNull('staff_reply')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Inquiry Details')
                    ->modalSubmitAction(false),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('mark_contacted')
                    ->label('Mark Contacted')
                    ->icon('heroicon-o-phone')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        $record->update(['status' => 'contacted']);
                        Notification::make()
                            ->title('Inquiry marked as contacted')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'contacted']))
                    ->action(function ($record) {
                        $record->update(['status' => 'completed']);
                        Notification::make()
                            ->title('Inquiry marked as completed')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\Action::make('send_reply')
                    ->label('Send Reply')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->form([
                        TextInput::make('subject')
                            ->required()
                            ->default('Re: Your Inquiry'),
                        Forms\Components\RichEditor::make('reply_message')
                            ->label('Message')
                            ->required()
                            ->helperText('This message will be sent to the customer\'s email'),
                    ])
                    ->action(function ($record, array $data) {
                        // Send email
                        Mail::to($record->email)->send(new InquiryReply($record, $data));
                        
                        // Save reply
                        $record->update([
                            'staff_reply' => $data['reply_message'],
                            'status' => 'contacted',
                        ]);
                        
                        Notification::make()
                            ->title('Reply sent successfully!')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                
                Tables\Actions\BulkAction::make('mark_contacted_bulk')
                    ->label('Mark as Contacted')
                    ->icon('heroicon-o-phone')
                    ->action(function ($records) {
                        $records->each->update(['status' => 'contacted']);
                        Notification::make()
                            ->title(count($records) . ' inquiries marked as contacted')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\BulkAction::make('mark_completed_bulk')
                    ->label('Mark as Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each->update(['status' => 'completed']);
                        Notification::make()
                            ->title(count($records) . ' inquiries marked as completed')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\BulkAction::make('mark_spam_bulk')
                    ->label('Mark as Spam')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        $records->each->update(['status' => 'spam']);
                        Notification::make()
                            ->title(count($records) . ' inquiries marked as spam')
                            ->warning()
                            ->send();
                    }),
            ]);
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
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
    
    // Add badge count for navigation
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}