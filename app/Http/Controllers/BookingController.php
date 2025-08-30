// app/Http/Controllers/BookingController.php
class BookingController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'photographer' => 'required|integer|exists:photographers,id',
            'package' => 'nullable|integer|exists:packages,id',
        ]);

        $photographer = Photographer::with('packages')->findOrFail($request->photographer);
        $selectedPackage = $request->filled('package')
            ? $photographer->packages()->findOrFail($request->package)
            : null;

        return view('bookings.create', compact('photographer','selectedPackage'));
    }
}
