{{-- resources/views/components/footer.blade.php --}}
<footer class="bg-gradient-to-r from-[#2a2a2a] via-[#1a1a1a] to-[#2a2a2a] text-white relative overflow-hidden border-t border-gray-700/30">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-96 h-96 bg-gray-500 rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-gray-400 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Main footer content -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 text-center sm:text-left">
            
            <!-- Company Info & Logo -->
            <div class="col-span-1 sm:col-span-2 lg:col-span-1 animate-fade-in-left flex flex-col items-center sm:items-start">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-white mb-3">
                        PickaPic
                    </h3>
                </div>
                
                <!-- Contact Info -->
                <div class="space-y-2 text-sm text-gray-300">
                    <div class="flex items-center space-x-2 justify-center sm:justify-start">
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span>+94 76 123 4567</span>
                    </div>
                    <div class="flex items-start space-x-2 justify-center sm:justify-start">
                        <svg class="w-4 h-4 text-gray-300 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span>No. 42, Temple Road,<br>Kandy 20000, Sri Lanka</span>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="animate-fade-in-up flex flex-col items-center sm:items-start" style="animation-delay: 0.2s;">
                <h4 class="font-semibold text-lg mb-6 text-white border-b border-gray-700 pb-2">Our Services</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="footer-link text-gray-300 flex items-center space-x-2 justify-center sm:justify-start">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                        <span>Wedding Photography</span>
                    </a></li>
                    <li><a href="#" class="footer-link text-gray-300 flex items-center space-x-2 justify-center sm:justify-start">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                        <span>Portrait Sessions</span>
                    </a></li>
                    <li><a href="#" class="footer-link text-gray-300 flex items-center space-x-2 justify-center sm:justify-start">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                        <span>Event Coverage</span>
                    </a></li>
                    <li><a href="#" class="footer-link text-gray-300 flex items-center space-x-2 justify-center sm:justify-start">
                        <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                        <span>Corporate Photography</span>
                    </a></li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="animate-fade-in-up flex flex-col items-center sm:items-start" style="animation-delay: 0.4s;">
                <h4 class="font-semibold text-lg mb-6 text-white border-b border-gray-700 pb-2">Quick Links</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('/client/client-dashboard') }}" class="footer-link text-gray-300">Home</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link text-gray-300">About Us</a></li>
                    <li><a href="{{ route('categories') }}" class="footer-link text-gray-300">Categories</a></li>
                    <li><a href="#" class="footer-link text-gray-300">Photographers</a></li>
                    <li><a href="#" class="footer-link text-gray-300">Contact</a></li>
                </ul>
            </div>

            <!-- Connect & Support -->
            <div class="animate-fade-in-right flex flex-col items-center sm:items-start" style="animation-delay: 0.6s;">
                <h4 class="font-semibold text-lg mb-6 text-white border-b border-gray-700 pb-2">Connect & Support</h4>
                
                <!-- Social Media -->
                <div class="mb-6">
                    <p class="text-gray-300 text-sm mb-3">Follow us for updates</p>
                    <div class="flex space-x-4 justify-center sm:justify-start">
                        
                        <!-- Instagram -->
                        <a href="#" class="social-icon bg-[#2a2a2a] hover:bg-white/10 p-2.5 rounded-full group border border-gray-600/30" aria-label="Instagram">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 2 .2 2.5.4.6.2 1 .5 1.5 1 .4.4.8.9 1 1.5.2.5.3 1.3.4 2.5.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 2-.4 2.5-.2.6-.5 1-1 1.5-.4.4-.9.8-1.5 1-.5.2-1.3.3-2.5.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-2-.2-2.5-.4-.6-.2-1-.5-1.5-1-.4-.4-.8-.9-1-1.5-.2-.5-.3-1.3-.4-2.5C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.9c.1-1.2.2-2 .4-2.5.2-.6.5-1 1-1.5.4-.4.9-.8 1.5-1 .5-.2 1.3-.3 2.5-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.8.1-1 .1-1.5.2-1.8.4-.5.2-.8.5-1.1.9-.3.3-.6.7-.9 1.1-.2.3-.3.8-.4 1.8-.1 1.3-.1 1.7-.1 4.8s0 3.5.1 4.8c.1 1 .2 1.5.4 1.8.2.5.5.8.9 1.1.3.3.7.6 1.1.9.3.2.8.3 1.8.4 1.3.1 1.7.1 4.8.1s3.5 0 4.8-.1c1-.1 1.5-.2 1.8-.4.5-.2.8-.5 1.1-.9.3-.3.6-.7.9-1.1.2-.3.3-.8.4-1.8.1-1.3.1-1.7.1-4.8s0-3.5-.1-4.8c-.1-1-.2-1.5-.4-1.8-.2-.5-.5-.8-.9-1.1-.3-.3-.7-.6-1.1-.9-.3-.2-.8-.3-1.8-.4-1.3-.1-1.7-.1-4.8-.1zm0 3.4a6.6 6.6 0 1 1 0 13.2 6.6 6.6 0 0 1 0-13.2zm0 1.8a4.8 4.8 0 1 0 0 9.6 4.8 4.8 0 0 0 0-9.6zm5.4-3.3a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3z"/>
                            </svg>
                        </a>

                        <!-- Twitter / X -->
                        <a href="#" class="social-icon bg-[#2a2a2a] hover:bg-white/10 p-2.5 rounded-full group border border-gray-600/30" aria-label="Twitter">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.954 4.569c-.885.392-1.83.656-2.825.775a4.94 4.94 0 0 0 2.163-2.724c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 0 0-8.39 4.482c-4.088-.205-7.72-2.165-10.148-5.144-1.29 2.213-.67 5.108 1.523 6.574a4.904 4.904 0 0 1-2.229-.616v.06c0 2.385 1.693 4.374 3.946 4.827a4.934 4.934 0 0 1-2.224.084c.626 1.956 2.444 3.377 4.6 3.418a9.869 9.869 0 0 1-6.102 2.104c-.396 0-.788-.023-1.176-.067a13.945 13.945 0 0 0 7.548 2.209c9.056 0 14.007-7.496 14.007-13.986 0-.21 0-.423-.015-.635a9.935 9.935 0 0 0 2.46-2.548z"/>
                            </svg>
                        </a>

                        <!-- Facebook -->
                        <a href="#" class="social-icon bg-[#2a2a2a] hover:bg-white/10 p-2.5 rounded-full group border border-gray-600/30" aria-label="Facebook">
                            <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.675 0h-21.35C.597 0 0 .597 0 1.326v21.348C0 23.403.597 24 1.326 24h11.495v-9.294H9.691V11.01h3.13V8.413c0-3.1 1.893-4.788 4.657-4.788 1.325 0 2.463.098 2.794.142v3.24l-1.918.001c-1.505 0-1.797.716-1.797 1.765v2.314h3.587l-.467 3.696h-3.12V24h6.116C23.403 24 24 23.403 24 22.674V1.326C24 .597 23.403 0 22.675 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                
                <!-- Quick Support Links -->
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="footer-link text-gray-300">Help Center</a></li>
                    <li><a href="#" class="footer-link text-gray-300">Privacy Policy</a></li>
                    <li><a href="#" class="footer-link text-gray-300">Terms of Service</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-700 bg-black/20 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 text-center sm:text-left">
                <div class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} PickaPic. All rights reserved. | Sri Lanka
                </div>
                <div class="flex space-x-6 text-sm justify-center sm:justify-start">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Sitemap</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Accessibility</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('styles')
<style>
.footer-link {
    transition: all 0.3s ease;
}
.footer-link:hover {
    color: #ffffff;
    transform: translateX(4px);
}
.social-icon {
    transition: all 0.3s ease;
}
.social-icon:hover {
    transform: translateY(-2px) scale(1.1);
    filter: brightness(1.2);
}
</style>
@endpush
