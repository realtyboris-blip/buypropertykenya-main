@php
    use App\Models\Settings;
    $settings = Settings::getWhatsAppSettings();
@endphp

@if(($settings['whatsapp_enabled'] ?? true) && !empty($settings['whatsapp_number']))
    <div class="whatsapp-floating-button {{ $settings['whatsapp_position'] ?? 'bottom-right' }}"
         data-desktop="{{ ($settings['whatsapp_show_on_desktop'] ?? true) ? 'true' : 'false' }}"
         data-mobile="{{ ($settings['whatsapp_show_on_mobile'] ?? true) ? 'true' : 'false' }}">
        
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}?text={{ urlencode($settings['whatsapp_message'] ?? 'Hello! I need more information about your properties.') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="whatsapp-link group"
           style="background-color: {{ $settings['whatsapp_background_color'] ?? '#075E54' }};"
           aria-label="{{ $settings['whatsapp_label'] ?? 'Chat with us on WhatsApp' }}">
            
            <!-- WhatsApp Icon -->
            <svg class="whatsapp-icon" 
                 style="color: {{ $settings['whatsapp_icon_color'] ?? '#25D366' }};"
                 xmlns="http://www.w3.org/2000/svg" 
                 viewBox="0 0 24 24" 
                 fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            
            @if($settings['whatsapp_label'] ?? false)
                <span class="whatsapp-label">{{ $settings['whatsapp_label'] }}</span>
            @endif
        </a>
    </div>

    <style>
        /* Floating Button Container */
        .whatsapp-floating-button {
            position: fixed;
            z-index: 9999;
            transition: all 0.3s ease;
        }
        
        /* Position: Bottom Right */
        .whatsapp-floating-button.bottom-right {
            bottom: 20px;
            right: 20px;
        }
        
        /* Position: Bottom Left */
        .whatsapp-floating-button.bottom-left {
            bottom: 20px;
            left: 20px;
        }
        
        /* Button Styling */
        .whatsapp-floating-button .whatsapp-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 18px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #ffffff;
            cursor: pointer;
            border: none;
            outline: none;
        }
        
        /* Hover Effect */
        .whatsapp-floating-button .whatsapp-link:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.25);
        }
        
        /* Active/Click Effect */
        .whatsapp-floating-button .whatsapp-link:active {
            transform: scale(0.95);
        }
        
        /* Icon Styling */
        .whatsapp-floating-button .whatsapp-icon {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
        }
        
        /* Label Styling */
        .whatsapp-floating-button .whatsapp-label {
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            line-height: 1.2;
        }
        
        /* Pulse Animation */
        @keyframes whatsappPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.4);
            }
            70% {
                box-shadow: 0 0 0 20px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }
        
        .whatsapp-floating-button .whatsapp-link {
            animation: whatsappPulse 2.5s infinite;
        }
        
        /* Stop pulse on hover */
        .whatsapp-floating-button .whatsapp-link:hover {
            animation: none;
        }
        
        /* Mobile Responsive */
        @media (max-width: 640px) {
            .whatsapp-floating-button .whatsapp-link {
                padding: 10px 14px;
            }
            
            .whatsapp-floating-button .whatsapp-label {
                font-size: 12px;
            }
            
            .whatsapp-floating-button .whatsapp-icon {
                width: 24px;
                height: 24px;
            }
            
            .whatsapp-floating-button.bottom-right {
                bottom: 15px;
                right: 15px;
            }
            
            .whatsapp-floating-button.bottom-left {
                bottom: 15px;
                left: 15px;
            }
        }
        
        /* Tablet Responsive */
        @media (min-width: 641px) and (max-width: 1024px) {
            .whatsapp-floating-button .whatsapp-link {
                padding: 11px 16px;
            }
            
            .whatsapp-floating-button .whatsapp-icon {
                width: 26px;
                height: 26px;
            }
            
            .whatsapp-floating-button .whatsapp-label {
                font-size: 13px;
            }
        }
        
        /* Hide on Print */
        @media print {
            .whatsapp-floating-button {
                display: none !important;
            }
        }
        
        /* Accessibility - Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            .whatsapp-floating-button .whatsapp-link {
                animation: none !important;
                transition: none !important;
            }
            
            .whatsapp-floating-button .whatsapp-link:hover {
                transform: none !important;
            }
        }
        
        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            .whatsapp-floating-button .whatsapp-link {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            }
        }
        
        /* High Contrast Mode */
        @media (prefers-contrast: high) {
            .whatsapp-floating-button .whatsapp-link {
                border: 2px solid #ffffff;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const widget = document.querySelector('.whatsapp-floating-button');
            if (!widget) return;
            
            // Check device type
            const isDesktop = window.innerWidth >= 768;
            const showOnDesktop = widget.dataset.desktop === 'true';
            const showOnMobile = widget.dataset.mobile === 'true';
            
            // Show/hide based on device settings
            if ((isDesktop && !showOnDesktop) || (!isDesktop && !showOnMobile)) {
                widget.style.display = 'none';
            }
            
            // Track WhatsApp button click
            const link = widget.querySelector('.whatsapp-link');
            if (link) {
                link.addEventListener('click', function(e) {
                    // Track click event for Google Analytics
                    if (typeof window.gtag !== 'undefined') {
                        gtag('event', 'whatsapp_click', {
                            'event_category': 'engagement',
                            'event_label': 'whatsapp_chat',
                            'value': 1
                        });
                    }
                    
                    // Track for Facebook Pixel
                    if (typeof window.fbq !== 'undefined') {
                        fbq('track', 'Contact', {
                            contact: 'whatsapp'
                        });
                    }
                    
                    // Track for custom analytics
                    if (typeof window._paq !== 'undefined') {
                        _paq.push(['trackEvent', 'WhatsApp', 'Click', 'Chat']);
                    }
                    
                    console.log('WhatsApp button clicked - opening chat');
                });
            }
        });
    </script>
@endif