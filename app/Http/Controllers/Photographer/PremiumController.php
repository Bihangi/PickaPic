<?php
// app/Http/Controllers/Photographer/PremiumController.php

namespace App\Http\Controllers\Photographer;

use App\Http\Controllers\Controller;
use App\Models\PremiumRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PremiumController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show upgrade page
     */
    public function index()
    {
        $user = Auth::user();
        $photographer = $user->photographer;

        if (!$photographer) {
            return redirect()->route('photographer.login')->with('error', 'Photographer profile not found.');
        }

        return view('photographer.premium-upgrade', compact('photographer'));
    }

    /**
     * Submit a new premium request
     */
    public function store(Request $request)
    {
        try {
            Log::info('Premium request started', $request->all());
            
            $user = Auth::user();
            $photographer = $user->photographer;

            if (!$photographer) {
                Log::error('Photographer profile not found for user: ' . $user->id);
                return response()->json([
                    'success' => false,
                    'message' => 'Photographer profile not found.'
                ], 400);
            }

            // Check if photographer already has pending request
            if ($photographer->hasPendingPremiumRequest()) {
                Log::warning('User has pending request', ['photographer_id' => $photographer->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending premium request. Please wait for approval.'
                ], 400);
            }

            // Validate the request
            $validator = validator($request->all(), [
                'package_type' => ['required', Rule::in(['monthly', 'quarterly', 'yearly'])],
                'amount_paid' => 'required|numeric|min:0',
                'payment_slip' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
                'message' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // Validate amount matches package - Updated package prices
            $packages = [
                'monthly' => ['price' => 2500],
                'quarterly' => ['price' => 6000], // Fixed from 7000 to 6000 as per your blade file
                'yearly' => ['price' => 20000] // Fixed from 25000 to 20000 as per your blade file
            ];
            
            $expectedAmount = $packages[$request->package_type]['price'];

            if ((float)$request->amount_paid != $expectedAmount) {
                Log::error('Amount mismatch', [
                    'expected' => $expectedAmount,
                    'received' => $request->amount_paid,
                    'package' => $request->package_type
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Amount paid does not match the selected package price.'
                ], 400);
            }

            // Check if file was uploaded
            if (!$request->hasFile('payment_slip')) {
                Log::error('No file uploaded');
                return response()->json([
                    'success' => false,
                    'message' => 'Payment slip file is required.'
                ], 400);
            }

            $file = $request->file('payment_slip');
            
            // Check if file is valid
            if (!$file->isValid()) {
                Log::error('Invalid file upload', ['error' => $file->getErrorMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file upload. Please try again.'
                ], 400);
            }

            // Store payment slip with a more unique name
            $filename = 'premium_' . $photographer->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $paymentSlipPath = $file->storeAs('premium-requests', $filename, 'public');

            if (!$paymentSlipPath) {
                Log::error('Failed to store file');
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload payment slip. Please try again.'
                ], 500);
            }

            // Create premium request
            $premiumRequest = PremiumRequest::create([
                'photographer_id' => $photographer->id,
                'package_type' => $request->package_type,
                'amount_paid' => $request->amount_paid,
                'payment_slip' => $paymentSlipPath,
                'message' => $request->message,
                'status' => 'pending'
            ]);

            Log::info('Premium request created successfully', [
                'request_id' => $premiumRequest->id,
                'photographer_id' => $photographer->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Premium request submitted successfully! We will review it within 24 hours.'
            ]);

        } catch (\Exception $e) {
            Log::error('Premium request error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again later.'
            ], 500);
        }
    }

    /**
     * Show all requests of the photographer
     */
    public function status()
    {
        $user = Auth::user();
        $photographer = $user->photographer;

        if (!$photographer) {
            return redirect()->route('photographer.login')->with('error', 'Photographer profile not found.');
        }

        $requests = $photographer->premiumRequests()->latest()->get();

        return view('photographer.premium-status', compact('photographer', 'requests'));
    }

    /**
     * Show a single premium request
     */
    public function show(PremiumRequest $premiumRequest)
    {
        $user = Auth::user();
        $photographer = $user->photographer;

        if (!$photographer || $premiumRequest->photographer_id !== $photographer->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('photographer.premium-show', compact('premiumRequest'));
    }
}