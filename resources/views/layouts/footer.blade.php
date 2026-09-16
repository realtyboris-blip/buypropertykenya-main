<footer class="bg-white pt-16 pb-0 mt-16 relative overflow-hidden">
    <!-- Decorative gradient elements -->
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 via-emerald-400 to-amber-400"></div>
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-emerald-100/40 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-100/30 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 pb-12 border-b border-gray-100">

            <!-- Logo + Description + Social -->
            <div>
                <a href="{{ route('home') }}" class="flex items-center space-x-3 mb-5">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #064e3b, #0d7a5f);">
                        <i class="fas fa-home text-amber-400 text-lg"></i>
                    </div>
                    <div>
                        <span class="text-lg font-bold text-gray-800 leading-tight block" style="font-family: 'Cormorant Garamond', serif;">BuyProperty</span>
                        <span class="text-xs font-semibold tracking-widest uppercase" style="color: #c9a84c;">Kenya</span>
                    </div>
                </a>
                <p class="text-sm leading-relaxed mb-6 text-gray-600" style="font-family: 'Inter', sans-serif;">
                    Connecting buyers, sellers, and renters with verified properties across Kenya — from Nairobi's top neighbourhoods to coastal retreats.
                </p>
                <div class="flex space-x-3">
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg" style="background: #f3f4f6; color: #6b7280; border: 1px solid rgba(201,168,76,0.2);">
                        <i class="fab fa-facebook-f text-sm hover:text-emerald-600 transition"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg" style="background: #f3f4f6; color: #6b7280; border: 1px solid rgba(201,168,76,0.2);">
                        <i class="fab fa-x-twitter text-sm hover:text-emerald-600 transition"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg" style="background: #f3f4f6; color: #6b7280; border: 1px solid rgba(201,168,76,0.2);">
                        <i class="fab fa-linkedin-in text-sm hover:text-emerald-600 transition"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg" style="background: #f3f4f6; color: #6b7280; border: 1px solid rgba(201,168,76,0.2);">
                        <i class="fab fa-instagram text-sm hover:text-emerald-600 transition"></i>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-lg" style="background: #f3f4f6; color: #6b7280; border: 1px solid rgba(201,168,76,0.2);">
                        <i class="fab fa-youtube text-sm hover:text-emerald-600 transition"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-5 text-gray-800" style="font-family: 'Inter', sans-serif;">Quick Links</h3>
                <div class="w-10 h-0.5 mb-6" style="background: linear-gradient(90deg, #c9a84c, #f0d78c);"></div>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('properties.index') }}?listing_type=sale" class="flex items-center gap-2 text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600 group">
                            <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0 transition-all duration-300 group-hover:scale-150" style="background: #c9a84c;"></span>
                            Properties For Sale
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('properties.index') }}?listing_type=rent" class="flex items-center gap-2 text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600 group">
                            <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0 transition-all duration-300 group-hover:scale-150" style="background: #c9a84c;"></span>
                            Rental Apartments
                        </a>
                    </li>
                    <li>
                        <a href="/projects" class="flex items-center gap-2 text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600 group">
                            <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0 transition-all duration-300 group-hover:scale-150" style="background: #c9a84c;"></span>
                            New Projects
                        </a>
                    </li>
                    <li>
                        <a href="/agents" class="flex items-center gap-2 text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600 group">
                            <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0 transition-all duration-300 group-hover:scale-150" style="background: #c9a84c;"></span>
                            Our Agents
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lead.form') }}" class="flex items-center gap-2 text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600 group">
                            <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0 transition-all duration-300 group-hover:scale-150" style="background: #c9a84c;"></span>
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lead.form') }}" class="flex items-center gap-2 text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600 group">
                            <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0 transition-all duration-300 group-hover:scale-150" style="background: #c9a84c;"></span>
                            FAQs
                        </a>
                    </li>
                </ul>
            </div>

            <!-- About blurb -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-5 text-gray-800" style="font-family: 'Inter', sans-serif;">About Us</h3>
                <div class="w-10 h-0.5 mb-6" style="background: linear-gradient(90deg, #c9a84c, #f0d78c);"></div>
                <p class="text-sm leading-relaxed text-gray-600" style="font-family: 'Inter', sans-serif; line-height: 1.8;">
                    BuyProperty Kenya offers a wide range of listings for sale and rent across the country. Whether you're seeking a family home in Karen or a high-end investment in Westlands, our team provides expert guidance, free consultations, and real-time insights into Kenya's evolving property market.
                </p>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-5 text-gray-800" style="font-family: 'Inter', sans-serif;">Contact</h3>
                <div class="w-10 h-0.5 mb-6" style="background: linear-gradient(90deg, #c9a84c, #f0d78c);"></div>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, #064e3b, #0d7a5f);">
                            <i class="fas fa-map-marker-alt text-xs text-amber-400"></i>
                        </div>
                        <span class="text-sm leading-relaxed text-gray-600">Kilimani, Nairobi, Kenya</span>
                    </li>
                    <li class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, #064e3b, #0d7a5f);">
                            <i class="fas fa-envelope text-xs text-amber-400"></i>
                        </div>
                        <a href="mailto:info@buypropertykenya.com" class="text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600">
                            info@buypropertykenya.com
                        </a>
                    </li>
                    <li class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, #064e3b, #0d7a5f);">
                            <i class="fas fa-phone text-xs text-amber-400"></i>
                        </div>
                        <a href="tel:+254702430127" class="text-sm transition-all duration-300 hover:text-emerald-600 text-gray-600">
                            +254 702 430 127
                        </a>
                    </li>
                </ul>

                <!-- Newsletter mini -->
                <div class="mt-7">
                    <p class="text-xs uppercase tracking-widest mb-3 font-semibold text-gray-500">Newsletter</p>
                    <form action="#" method="POST" class="flex">
                        @csrf
                        <input type="email" name="email" placeholder="Your email address"
                               class="flex-1 px-3 py-2.5 text-sm text-gray-700 placeholder-gray-400 border border-gray-200 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300">
                        <button type="submit"
                                class="px-4 py-2.5 text-sm font-medium rounded-r-lg transition-all duration-300 hover:opacity-90 hover:scale-105 flex-shrink-0"
                                style="background: linear-gradient(135deg, #064e3b, #0d7a5f); color: white;">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="py-6 flex flex-col sm:flex-row justify-between items-center gap-3">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} BuyProperty Kenya. All rights reserved.
            </p>
            <div class="flex gap-6">
                <a href="#" class="text-xs text-gray-500 hover:text-emerald-600 transition duration-300">Privacy Policy</a>
                <a href="#" class="text-xs text-gray-500 hover:text-emerald-600 transition duration-300">Terms of Service</a>
                <a href="#" class="text-xs text-gray-500 hover:text-emerald-600 transition duration-300">Cookie Policy</a>
            </div>
            <p class="text-xs text-gray-500">
                Developed with <span style="color: #c9a84c;">♥</span> in Nairobi
            </p>
        </div>
    </div>
</footer>

<style>
    /* Hover animations */
    .hover\:scale-110:hover {
        transform: scale(1.1);
    }
    
    /* Social icon hover effect */
    .social-icon {
        transition: all 0.3s ease;
    }
    
    .social-icon:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(201, 168, 76, 0.25);
    }
</style>