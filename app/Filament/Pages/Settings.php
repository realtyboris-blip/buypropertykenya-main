<?php

namespace App\Filament\Pages;

use App\Models\Settings as SettingsModel;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;
    
    // SMS Settings
    public $sms_enabled;
    public $sales_team_phone;
    public $sales_team_phones;
    
    // WhatsApp Settings
    public $whatsapp_enabled;
    public $whatsapp_number;
    public $whatsapp_message;
    public $whatsapp_position;
    public $whatsapp_icon_color;
    public $whatsapp_background_color;
    public $whatsapp_show_on_desktop;
    public $whatsapp_show_on_mobile;
    public $whatsapp_label;
    
    // General Settings
    public $site_name;
    public $site_email;
    public $site_phone;
    public $site_address;
    
    public function mount()
    {
        // Load settings from database
        $settings = SettingsModel::all()->pluck('value', 'key')->toArray();
        
        // SMS Settings
        $this->sms_enabled = $settings['sms_enabled'] ?? false;
        $this->sales_team_phone = $settings['sales_team_phone'] ?? '';
        $this->sales_team_phones = $settings['sales_team_phones'] ?? '';
        
        // WhatsApp Settings
        $this->whatsapp_enabled = $settings['whatsapp_enabled'] ?? true;
        $this->whatsapp_number = $settings['whatsapp_number'] ?? '+254700000000';
        $this->whatsapp_message = $settings['whatsapp_message'] ?? 'Hello! I need more information about your properties.';
        $this->whatsapp_position = $settings['whatsapp_position'] ?? 'bottom-right';
        $this->whatsapp_icon_color = $settings['whatsapp_icon_color'] ?? '#25D366';
        $this->whatsapp_background_color = $settings['whatsapp_background_color'] ?? '#075E54';
        $this->whatsapp_show_on_desktop = $settings['whatsapp_show_on_desktop'] ?? true;
        $this->whatsapp_show_on_mobile = $settings['whatsapp_show_on_mobile'] ?? true;
        $this->whatsapp_label = $settings['whatsapp_label'] ?? 'Chat with us on WhatsApp';
        
        // General Settings
        $this->site_name = $settings['site_name'] ?? config('app.name', 'BuyProperty Kenya');
        $this->site_email = $settings['site_email'] ?? '';
        $this->site_phone = $settings['site_phone'] ?? '';
        $this->site_address = $settings['site_address'] ?? '';
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // General Settings Section
                Section::make('General Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('site_name')
                                    ->label('Site Name')
                                    ->placeholder('Enter site name')
                                    ->helperText('The name of your website'),
                                
                                TextInput::make('site_email')
                                    ->label('Site Email')
                                    ->email()
                                    ->placeholder('admin@example.com')
                                    ->helperText('Primary contact email for the website'),
                                
                                TextInput::make('site_phone')
                                    ->label('Site Phone')
                                    ->placeholder('+254700000000')
                                    ->helperText('Primary contact phone number'),
                                
                                TextInput::make('site_address')
                                    ->label('Site Address')
                                    ->placeholder('Nairobi, Kenya')
                                    ->helperText('Physical address of your office'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),
                
                // SMS Settings Section
                Section::make('SMS Settings')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('sms_enabled')
                                    ->label('Enable SMS Notifications')
                                    ->helperText('Enable or disable SMS notifications for leads'),
                                
                                TextInput::make('sales_team_phone')
                                    ->label('Primary Sales Team Phone')
                                    ->placeholder('+254712345678')
                                    ->helperText('Main shared number for all agents'),
                                
                                TextInput::make('sales_team_phones')
                                    ->label('Additional Phone Numbers')
                                    ->placeholder('+254712345678,+254798765432')
                                    ->helperText('Comma-separated list of backup numbers'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(true),
                
                // WhatsApp Settings Section
                Section::make('WhatsApp Settings')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->description('Configure the WhatsApp floating button that appears on your website')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('whatsapp_enabled')
                                    ->label('Enable WhatsApp Button')
                                    ->helperText('Show/hide the WhatsApp floating button on the website')
                                    ->columnSpan(2),
                                
                                TextInput::make('whatsapp_number')
                                    ->label('WhatsApp Phone Number')
                                    ->required()
                                    ->placeholder('+254700000000')
                                    ->helperText('Enter the phone number with country code (e.g., +254700000000)')
                                    ->columnSpan(2),
                                
                                TextInput::make('whatsapp_label')
                                    ->label('Button Label')
                                    ->placeholder('Chat with us on WhatsApp')
                                    ->helperText('Text that appears on the button')
                                    ->columnSpan(2),
                                
                                Textarea::make('whatsapp_message')
                                    ->label('Default Message')
                                    ->placeholder('Hello! I need more information about your properties.')
                                    ->helperText('This message will be pre-filled when the chat opens')
                                    ->rows(3)
                                    ->columnSpan(2),
                                
                                Select::make('whatsapp_position')
                                    ->label('Button Position')
                                    ->options([
                                        'bottom-right' => 'Bottom Right',
                                        'bottom-left' => 'Bottom Left',
                                    ])
                                    ->helperText('Choose where the button should appear on the screen')
                                    ->columnSpan(2),
                                
                                ColorPicker::make('whatsapp_icon_color')
                                    ->label('Icon Color')
                                    ->default('#25D366')
                                    ->helperText('Color of the WhatsApp icon'),
                                
                                ColorPicker::make('whatsapp_background_color')
                                    ->label('Background Color')
                                    ->default('#075E54')
                                    ->helperText('Background color of the button'),
                                
                                Toggle::make('whatsapp_show_on_desktop')
                                    ->label('Show on Desktop')
                                    ->default(true)
                                    ->helperText('Display button on desktop devices'),
                                
                                Toggle::make('whatsapp_show_on_mobile')
                                    ->label('Show on Mobile')
                                    ->default(true)
                                    ->helperText('Display button on mobile devices'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(false),
                
                // Preview Section
                Section::make('Preview')
                    ->icon('heroicon-o-eye')
                    ->description('Preview of the WhatsApp button')
                    ->schema([
                        \Filament\Forms\Components\View::make('filament.pages.whatsapp-preview')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(true),
            ]);
    }
    
    public function save()
    {
        try {
            // Save all settings to database
            $settings = [
                // SMS Settings
                'sms_enabled' => $this->sms_enabled,
                'sales_team_phone' => $this->sales_team_phone,
                'sales_team_phones' => $this->sales_team_phones,
                
                // WhatsApp Settings
                'whatsapp_enabled' => $this->whatsapp_enabled,
                'whatsapp_number' => $this->whatsapp_number,
                'whatsapp_message' => $this->whatsapp_message,
                'whatsapp_position' => $this->whatsapp_position,
                'whatsapp_icon_color' => $this->whatsapp_icon_color,
                'whatsapp_background_color' => $this->whatsapp_background_color,
                'whatsapp_show_on_desktop' => $this->whatsapp_show_on_desktop,
                'whatsapp_show_on_mobile' => $this->whatsapp_show_on_mobile,
                'whatsapp_label' => $this->whatsapp_label,
                
                // General Settings
                'site_name' => $this->site_name,
                'site_email' => $this->site_email,
                'site_phone' => $this->site_phone,
                'site_address' => $this->site_address,
            ];
            
            foreach ($settings as $key => $value) {
                // Determine type
                $type = 'text';
                if (is_bool($value)) {
                    $type = 'boolean';
                    $value = $value ? 'true' : 'false';
                } elseif (is_int($value)) {
                    $type = 'integer';
                } elseif (is_array($value)) {
                    $type = 'array';
                    $value = json_encode($value);
                }
                
                SettingsModel::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group' => $this->getGroupForKey($key),
                        'type' => $type,
                        'is_public' => true,
                    ]
                );
            }
            
            // Also update config for immediate use
            config([
                'app.name' => $this->site_name ?? config('app.name'),
            ]);
            
            Notification::make()
                ->title('Settings saved successfully!')
                ->success()
                ->duration(5000)
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error saving settings')
                ->body($e->getMessage())
                ->danger()
                ->duration(5000)
                ->send();
        }
    }
    
    protected function getGroupForKey($key)
    {
        if (str_starts_with($key, 'whatsapp')) {
            return 'whatsapp';
        } elseif (str_starts_with($key, 'sms') || str_starts_with($key, 'sales_team')) {
            return 'communication';
        } elseif (str_starts_with($key, 'site_')) {
            return 'general';
        }
        return 'general';
    }
}