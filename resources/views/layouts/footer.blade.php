<!-- Footer -->
<footer class="bg-gradient-to-r from-black to-[#222] text-white px-6 sm:px-12 py-16">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10 text-sm text-center md:text-left ml-10">

        <!-- Explore & Connect -->
        <div class="space-y-3 animate-fade-in-left">
            <h4 class="font-semibold text-lg">Explore & Connect</h4>
            <ul class="space-y-3">
                <li><a href="#" class="hover:underline">Home</a></li>
                <li><a href="#" class="hover:underline">Packages</a></li>
                <li><a href="#" class="hover:underline">Sign In / Sign Up</a></li>
            </ul>
        </div>

        <!-- Company -->
        <div class="space-y-3 animate-fade-in-left">
            <h4 class="font-semibold text-lg">Company</h4>
            <ul class="space-y-1.5">
                <li><a href="#" class="hover:underline">About Us</a></li>
                <li><a href="#" class="hover:underline">Privacy</a></li>
                <li><a href="#" class="hover:underline">News</a></li>
                <li><a href="#" class="hover:underline">Support</a></li>
            </ul>
        </div>

        <!-- Contact Us -->
        <div class="space-y-3 animate-fade-in-right">
            <h4 class="font-semibold text-lg">Contact Us</h4>
            <p class="leading-relaxed">
                +94 76 123 4567<br>
                No. 42, Temple Road,<br>
                Kandy 20000, Sri Lanka
            </p>
        </div>

        <!-- Social Media -->
        <div class="space-y-3 animate-fade-in-right">
            <h4 class="font-semibold text-lg">Follow Us</h4>
            <div class="flex justify-center md:justify-start space-x-4">
                <a href="#"><img src="{{ Vite::asset('resources/images/facebook-icon.svg') }}" alt="Facebook" class="w-6 h-6 hover:scale-110 transition-transform"></a>
                <a href="#"><img src="{{ Vite::asset('resources/images/instagram-icon.svg') }}" alt="Instagram" class="w-6 h-6 hover:scale-110 transition-transform"></a>
                <a href="#"><img src="{{ Vite::asset('resources/images/twitter-icon.svg') }}" alt="Twitter" class="w-6 h-6 hover:scale-110 transition-transform"></a>
            </div>
        </div>
    </div>

    <!-- Divider & Copyright -->
    <div class="border-t border-gray-700 mt-12 pt-6 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} PickaPic. All rights reserved.
    </div>
</footer>
