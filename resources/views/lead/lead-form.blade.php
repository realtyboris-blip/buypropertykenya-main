@extends('layouts.app')

@section('title', 'Request Property Information - BuyProperty Kenya')
@section('description', 'Fill in your details to request property information from our sales team.')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&display=swap');

    :root {
        --bp-emerald-deep: #064e3b;
        --bp-emerald: #0d7a5f;
        --bp-emerald-soft: #e8f1ec;
        --bp-gold: #c9a84c;
        --bp-gold-soft: #f0d78c;
        --bp-cream: #f7f3e9;
    }

    .bp-display {
        font-family: 'Cormorant Garamond', serif;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .slide-in {
        animation: slideIn 0.5s ease-out;
    }

    .step-indicator {
        transition: all 0.5s ease;
    }

    .step-indicator.active {
        background: linear-gradient(135deg, var(--bp-emerald-deep), var(--bp-emerald));
        color: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(6, 78, 59, 0.3);
    }

    .step-indicator.completed {
        background: var(--bp-emerald);
        color: #fff;
    }

    .step-line {
        transition: all 0.5s ease;
    }

    .step-line.completed {
        background: var(--bp-emerald);
    }

    input:focus,
    select:focus,
    textarea:focus {
        box-shadow: 0 0 0 3px rgba(13, 122, 95, 0.15);
        border-color: var(--bp-emerald);
    }

    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23064e3b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 10px;
        padding-right: 36px;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
        animation: slideIn 0.5s ease-out;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--bp-emerald-deep) 0%, var(--bp-emerald) 60%, #053d2f 100%);
        box-shadow: 0 8px 24px -10px rgba(6, 78, 59, 0.6);
        transition: all 0.3s ease;
    }

    .btn-primary:hover:not(:disabled) {
        transform: scale(1.02);
        box-shadow: 0 12px 32px -10px rgba(6, 78, 59, 0.8);
    }

    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .btn-secondary {
        border: 2px solid var(--bp-emerald);
        color: var(--bp-emerald-deep);
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: var(--bp-emerald-soft);
        transform: scale(1.02);
    }

    .form-card {
        box-shadow: 0 30px 60px -20px rgba(6, 78, 59, .45);
    }

    /* Loading spinner */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Success animation */
    .success-check {
        animation: checkPop 0.5s ease;
    }
    
    @keyframes checkPop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 min-h-screen flex items-center py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto fade-in-up">
            <!-- Logo/Brand Section -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl shadow-lg mb-4" style="background: linear-gradient(135deg, var(--bp-emerald-deep), var(--bp-emerald));">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold" style="color: var(--bp-emerald-deep);">BuyProperty <span style="color: var(--bp-gold);">Kenya</span></h2>
                <p class="text-gray-600 mt-1">Your Dream Home Awaits</p>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden form-card">

                <!-- Header -->
                <div class="px-6 py-6 relative overflow-hidden" style="background: linear-gradient(135deg, var(--bp-emerald-deep) 0%, var(--bp-emerald) 60%, #053d2f 100%);">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-white opacity-5 rounded-full"></div>

                    <h1 class="bp-display text-2xl md:text-3xl font-bold text-white text-center relative z-10">
                        Request Property Information
                    </h1>
                    <p class="text-emerald-200 text-center mt-1 relative z-10 text-sm" style="color: var(--bp-gold-soft);">
                        Fill in your details and our sales team will contact you
                    </p>

                    <!-- Step Progress -->
                    <div class="mt-6 relative z-10">
                        <div class="flex items-center justify-between max-w-xs mx-auto">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center">
                                <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-white/20 text-white transition-all duration-500 active" data-step="1">
                                    1
                                </div>
                                <span class="text-white/70 text-[10px] mt-1 uppercase tracking-wider">Contact</span>
                            </div>
                            <div class="step-line flex-1 h-0.5 bg-white/20 mx-2" data-step="1"></div>

                            <!-- Step 2 -->
                            <div class="flex flex-col items-center">
                                <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-white/20 text-white transition-all duration-500" data-step="2">
                                    2
                                </div>
                                <span class="text-white/70 text-[10px] mt-1 uppercase tracking-wider">Details</span>
                            </div>
                            <div class="step-line flex-1 h-0.5 bg-white/20 mx-2" data-step="2"></div>

                            <!-- Step 3 -->
                            <div class="flex flex-col items-center">
                                <div class="step-indicator w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-white/20 text-white transition-all duration-500" data-step="3">
                                    3
                                </div>
                                <span class="text-white/70 text-[10px] mt-1 uppercase tracking-wider">Review</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Body -->
                <form id="leadForm" class="px-6 py-6" method="POST" action="{{ route('lead.submit') }}">
                    @csrf

                    <!-- Step 1: Contact Information -->
                    <div class="form-step active" data-step="1">
                        <div class="space-y-5">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" required
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                           style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);"
                                           placeholder="Enter your full name">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" required
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                           style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);"
                                           placeholder="you@example.com">
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                        Phone Number <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="phone" id="phone" required
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                           style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);"
                                           placeholder="+254 XXX XXX XXX">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                        Preferred Contact
                                    </label>
                                    <select name="contact_method" id="contact_method"
                                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                            style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);">
                                        <option value="phone">📞 Phone Call</option>
                                        <option value="whatsapp">💬 WhatsApp</option>
                                        <option value="email">✉️ Email</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Property Details -->
                    <div class="form-step" data-step="2">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                    Interested In <span class="text-red-500">*</span>
                                </label>
                                <select name="inquiry_type" id="inquiry_type" required
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                        style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);">
                                    <option value="">Select an option</option>
                                    <option value="buying">🏠 Buying a Property</option>
                                    <option value="selling">💰 Selling a Property</option>
                                    <option value="renting">🔑 Renting a Property</option>
                                    <option value="valuation">📊 Property Valuation</option>
                                    <option value="consultation">💬 General Consultation</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                    Property Budget (KES)
                                </label>
                                <select name="budget" id="budget"
                                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                        style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);">
                                    <option value="">Select budget range</option>
                                    <option value="1-5M">KSh 1M - 5M</option>
                                    <option value="5-10M">KSh 5M - 10M</option>
                                    <option value="10-20M">KSh 10M - 20M</option>
                                    <option value="20-50M">KSh 20M - 50M</option>
                                    <option value="50M+">KSh 50M+</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1.5" style="color: var(--bp-emerald-deep);">
                                    Message / Specific Requirements
                                </label>
                                <textarea name="message" id="message" rows="3"
                                          class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none transition-all duration-200 text-sm"
                                          style="background: var(--bp-cream); border-color: rgba(6,78,59,.12);"
                                          placeholder="Tell us what you're looking for..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Review & Submit -->
                    <div class="form-step" data-step="3">
                        <div class="space-y-5">
                            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                                <h3 class="font-semibold text-emerald-800 mb-2">📋 Review Your Information</h3>
                                <div class="space-y-1 text-sm text-gray-600" id="reviewData">
                                    <p><span class="font-medium">Name:</span> <span id="reviewName">-</span></p>
                                    <p><span class="font-medium">Email:</span> <span id="reviewEmail">-</span></p>
                                    <p><span class="font-medium">Phone:</span> <span id="reviewPhone">-</span></p>
                                    <p><span class="font-medium">Interested In:</span> <span id="reviewType">-</span></p>
                                    <p><span class="font-medium">Budget:</span> <span id="reviewBudget">-</span></p>
                                    <p><span class="font-medium">Message:</span> <span id="reviewMessage">-</span></p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <input type="checkbox" name="agree" id="agree" required
                                       class="h-5 w-5 rounded border-gray-300 mt-0.5 focus:ring-2 focus:ring-emerald-500"
                                       style="accent-color: var(--bp-emerald);">
                                <label for="agree" class="text-sm text-gray-600">
                                    I agree to receive property updates and communications
                                </label>
                            </div>

                            <button type="submit" id="submitBtn"
                                    class="w-full text-white font-semibold py-3.5 px-6 rounded-xl transition-all duration-300 transform hover:scale-[1.02] btn-primary">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Submit Request
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between gap-4 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" id="prevBtn"
                                class="px-6 py-2.5 rounded-xl font-medium transition-all duration-300 btn-secondary text-sm hidden">
                            ← Back
                        </button>
                        <div class="flex-1"></div>
                        <button type="button" id="nextBtn"
                                class="px-6 py-2.5 rounded-xl font-medium transition-all duration-300 text-white text-sm btn-primary">
                            Next →
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="px-6 py-3 border-t" style="background: var(--bp-cream); border-color: rgba(6,78,59,.08);">
                    <div class="flex flex-wrap items-center justify-center gap-4 text-xs">
                        <div class="flex items-center gap-1.5" style="color: #5b6862;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: var(--bp-emerald);">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Secure & Confidential</span>
                        </div>
                        <div class="flex items-center gap-1.5" style="color: #5b6862;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: var(--bp-emerald);">
                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path>
                            </svg>
                            <span>24hr Response</span>
                        </div>
                        <div class="flex items-center gap-1.5" style="color: #5b6862;">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="color: var(--bp-gold);">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path>
                            </svg>
                            <span>Verified Properties</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Step Navigation
        let currentStep = 1;
        const totalSteps = 3;
        const formSteps = document.querySelectorAll('.form-step');
        const stepIndicators = document.querySelectorAll('.step-indicator');
        const stepLines = document.querySelectorAll('.step-line');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        function updateSteps() {
            // Show/hide form steps
            formSteps.forEach((step, index) => {
                step.classList.toggle('active', index + 1 === currentStep);
            });

            // Update indicators
            stepIndicators.forEach((indicator, index) => {
                const stepNum = index + 1;
                indicator.classList.remove('active', 'completed');
                if (stepNum === currentStep) {
                    indicator.classList.add('active');
                } else if (stepNum < currentStep) {
                    indicator.classList.add('completed');
                }
            });

            // Update lines
            stepLines.forEach((line, index) => {
                const stepNum = index + 1;
                line.classList.toggle('completed', stepNum < currentStep);
            });

            // Show/hide buttons
            prevBtn.classList.toggle('hidden', currentStep === 1);
            if (currentStep === totalSteps) {
                nextBtn.textContent = 'Submit';
                nextBtn.type = 'submit';
            } else {
                nextBtn.textContent = 'Next →';
                nextBtn.type = 'button';
            }

            // Update review data on step 3
            if (currentStep === 3) {
                updateReviewData();
            }
        }

        function updateReviewData() {
            document.getElementById('reviewName').textContent = document.getElementById('name').value || '-';
            document.getElementById('reviewEmail').textContent = document.getElementById('email').value || '-';
            document.getElementById('reviewPhone').textContent = document.getElementById('phone').value || '-';
            
            const typeSelect = document.getElementById('inquiry_type');
            const typeText = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
            document.getElementById('reviewType').textContent = typeText;
            
            const budgetSelect = document.getElementById('budget');
            const budgetText = budgetSelect.options[budgetSelect.selectedIndex]?.text || '-';
            document.getElementById('reviewBudget').textContent = budgetText;
            
            document.getElementById('reviewMessage').textContent = document.getElementById('message').value || '-';
        }

        // Next button handler
        nextBtn.addEventListener('click', function(e) {
            if (currentStep === totalSteps) {
                // Submit the form
                document.getElementById('leadForm').submit();
                return;
            }

            // Validate current step
            const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#ef4444';
                    field.classList.add('border-red-500');
                    valid = false;
                } else {
                    field.style.borderColor = '';
                    field.classList.remove('border-red-500');
                }
            });

            if (!valid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please fill in all required fields',
                    text: 'All fields marked with * are required.',
                    confirmButtonColor: '#0d7a5f',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (currentStep < totalSteps) {
                currentStep++;
                updateSteps();
            }
        });

        // Previous button handler
        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateSteps();
            }
        });

        // Initialize
        updateSteps();

        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.startsWith('0') && value.length > 1) {
                    value = '+254' + value.substring(1);
                }
                if (value.startsWith('254') && !value.startsWith('+')) {
                    value = '+' + value;
                }
                e.target.value = value;
            });
        }

        // Form submission with enhanced feedback
        document.getElementById('leadForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Check if agree checkbox is checked
            const agreeCheckbox = document.getElementById('agree');
            if (!agreeCheckbox.checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please agree to the terms',
                    text: 'You must agree to receive property updates and communications.',
                    confirmButtonColor: '#0d7a5f',
                    confirmButtonText: 'OK'
                });
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

            const csrfToken = document.querySelector('input[name="_token"]')?.value ||
                              document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route("lead.submit") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message with email status
                    let html = '<strong>Thank you for your interest!</strong><br><br>';
                    
                    if (data.email_sent) {
                        html += '✅ A confirmation email has been sent to:<br>';
                        html += '<span class="text-emerald-600 font-semibold text-lg">' + data.email + '</span><br><br>';
                        html += '📧 Please check your inbox (and spam folder) for the confirmation.<br><br>';
                        html += 'Our sales team will contact you shortly.';
                    } else {
                        html += '⚠️ Your request has been received, but we encountered an issue sending the confirmation email.<br><br>';
                        html += 'Our team will contact you directly at:<br>';
                        html += '<span class="text-emerald-600 font-semibold text-lg">' + data.email + '</span><br><br>';
                        html += 'We apologize for any inconvenience.';
                    }
                    
                    Swal.fire({
                        icon: data.email_sent ? 'success' : 'warning',
                        title: data.email_sent ? 'Request Submitted! 🎉' : 'Request Submitted!',
                        html: html,
                        confirmButtonColor: '#0d7a5f',
                        confirmButtonText: 'Great!',
                        background: '#ffffff',
                        iconColor: '#0d7a5f',
                        timer: data.email_sent ? 8000 : 6000,
                        timerProgressBar: true
                    });
                    
                    // Reset form
                    document.getElementById('leadForm').reset();
                    currentStep = 1;
                    updateSteps();
                    
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: data.message || 'Something went wrong. Please try again.',
                        confirmButtonColor: '#0d7a5f'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Connection Error',
                    text: 'Unable to submit your request. Please check your connection and try again.',
                    confirmButtonColor: '#0d7a5f'
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush
@endsection