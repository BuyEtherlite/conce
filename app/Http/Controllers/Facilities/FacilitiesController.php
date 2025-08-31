<?php

namespace App\Http\Controllers\Facilities;

use App\Http\Controllers\Controller;
use App\Models\Facilities\Facility;
use App\Models\Facilities\FacilityDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilitiesController extends Controller
{
    public function index()
    {
        $facilities = Facility::with(['bookings', 'maintenance', 'documents'])
                             ->paginate(15);
        
        return view('facilities.index', compact('facilities'));
    }

    public function create()
    {
        $facilityTypes = [
            'hall' => 'Community Hall',
            'sports_center' => 'Sports Center',
            'pool' => 'Swimming Pool',
            'park' => 'Park/Recreation Area',
            'conference_room' => 'Conference Room',
            'multipurpose' => 'Multi-Purpose Room'
        ];

        $amenities = [
            'parking' => 'Parking Available',
            'catering' => 'Catering Kitchen',
            'audio_visual' => 'Audio/Visual Equipment',
            'air_conditioning' => 'Air Conditioning',
            'wifi' => 'WiFi Access',
            'changing_rooms' => 'Changing Rooms',
            'lighting' => 'Lighting System',
            'sound_system' => 'Sound System'
        ];

        return view('facilities.create', compact('facilityTypes', 'amenities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'facility_type' => 'required|string',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'operating_days' => 'required|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $facility = Facility::create($request->all());

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $this->uploadDocuments($facility, $request->file('documents'));
        }

        return redirect()->route('facilities.index')
                        ->with('success', 'Facility created successfully.');
    }

    public function show(Facility $facility)
    {
        $facility->load(['bookings', 'maintenance', 'documents', 'gateTakings']);
        
        return view('facilities.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        $facilityTypes = [
            'hall' => 'Community Hall',
            'sports_center' => 'Sports Center',
            'pool' => 'Swimming Pool',
            'park' => 'Park/Recreation Area',
            'conference_room' => 'Conference Room',
            'multipurpose' => 'Multi-Purpose Room'
        ];

        $amenities = [
            'parking' => 'Parking Available',
            'catering' => 'Catering Kitchen',
            'audio_visual' => 'Audio/Visual Equipment',
            'air_conditioning' => 'Air Conditioning',
            'wifi' => 'WiFi Access',
            'changing_rooms' => 'Changing Rooms',
            'lighting' => 'Lighting System',
            'sound_system' => 'Sound System'
        ];

        return view('facilities.edit', compact('facility', 'facilityTypes', 'amenities'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'facility_name' => 'required|string|max:255',
            'facility_type' => 'required|string',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
            'hourly_rate' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'operating_days' => 'required|array',
            'documents.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);

        $facility->update($request->all());

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $this->uploadDocuments($facility, $request->file('documents'));
        }

        return redirect()->route('facilities.index')
                        ->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        
        return redirect()->route('facilities.index')
                        ->with('success', 'Facility deleted successfully.');
    }

    private function uploadDocuments(Facility $facility, array $files)
    {
        foreach ($files as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('facility_documents', $filename, 'public');

            FacilityDocument::create([
                'facility_id' => $facility->id,
                'document_name' => $file->getClientOriginalName(),
                'document_type' => $file->getClientOriginalExtension(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'uploaded_by' => auth()->user()->name
            ]);
        }
    }

    public function downloadDocument(FacilityDocument $document)
    {
        return Storage::disk('public')->download($document->file_path, $document->document_name);
    }

    public function deleteDocument(FacilityDocument $document)
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        
        return redirect()->back()->with('success', 'Document deleted successfully.');
    }
}
