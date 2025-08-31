<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventPermitController extends Controller
{
    public function index()
    {
        $permits = DB::table('event_permits')
            ->select('event_permits.*')
            ->orderBy('event_permits.created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_permits' => DB::table('event_permits')->count(),
            'pending_permits' => DB::table('event_permits')->where('status', 'pending')->count(),
            'approved_permits' => DB::table('event_permits')->where('status', 'approved')->count(),
            'rejected_permits' => DB::table('event_permits')->where('status', 'rejected')->count(),
        ];

        $upcomingEvents = DB::table('event_permits')
            ->leftJoin('event_categories', 'event_permits.event_category_id', '=', 'event_categories.id')
            ->select('event_permits.*', 'event_categories.name as category_name')
            ->where('event_permits.status', 'approved')
            ->where('event_permits.event_date', '>=', now())
            ->orderBy('event_permits.event_date', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($event) {
                $event->days_to_event = now()->diffInDays($event->event_date);
                return $event;
            });

        return view('events.permits.index', compact('permits', 'stats', 'upcomingEvents'));
    }

    public function create()
    {
        $categories = DB::table('event_categories')->get();

        return view('events.permits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue' => 'required|string|max:255',
            'expected_attendance' => 'required|integer|min:1',
            'organizer_name' => 'required|string|max:255',
            'organizer_contact' => 'required|string|max:255',
            'organizer_email' => 'required|email',
            'event_category_id' => 'required|exists:event_categories,id',
        ]);

        $permitData = $request->all();
        $permitData['application_number'] = 'EP' . date('Y') . str_pad(DB::table('event_permits')->count() + 1, 4, '0', STR_PAD_LEFT);
        $permitData['status'] = 'pending';
        $permitData['created_at'] = now();
        $permitData['updated_at'] = now();

        DB::table('event_permits')->insert($permitData);

        return redirect()->route('events.permits.index')->with('success', 'Event permit application submitted successfully.');
    }

    public function show($id)
    {
        $permit = DB::table('event_permits')
            ->leftJoin('event_categories', 'event_permits.event_category_id', '=', 'event_categories.id')
            ->select('event_permits.*', 'event_categories.name as category_name')
            ->where('event_permits.id', $id)
            ->first();

        if (!$permit) {
            abort(404);
        }

        $clearances = DB::table('event_permit_clearances')
            ->where('event_permit_id', $id)
            ->get();

        $fees = DB::table('event_permit_fees')
            ->where('event_permit_id', $id)
            ->get();

        $documents = DB::table('event_permit_documents')
            ->where('event_permit_id', $id)
            ->get();

        return view('events.permits.show', compact('permit', 'clearances', 'fees', 'documents'));
    }

    public function edit($id)
    {
        $permit = DB::table('event_permits')->find($id);

        if (!$permit) {
            abort(404);
        }

        $categories = DB::table('event_categories')->get();

        return view('events.permits.edit', compact('permit', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'venue' => 'required|string|max:255',
            'expected_attendance' => 'required|integer|min:1',
            'organizer_name' => 'required|string|max:255',
            'organizer_contact' => 'required|string|max:255',
            'organizer_email' => 'required|email',
            'event_category_id' => 'required|exists:event_categories,id',
            'status' => 'sometimes|in:pending,approved,rejected,expired',
        ]);

        $updateData = $request->all();
        $updateData['updated_at'] = now();

        DB::table('event_permits')->where('id', $id)->update($updateData);

        return redirect()->route('events.permits.show', $id)->with('success', 'Event permit updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('event_permits')->where('id', $id)->delete();

        return redirect()->route('events.permits.index')->with('success', 'Event permit deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $permits = DB::table('event_permits')
            ->leftJoin('event_categories', 'event_permits.event_category_id', '=', 'event_categories.id')
            ->select('event_permits.*', 'event_categories.name as category_name')
            ->where(function($q) use ($query) {
                $q->where('event_permits.event_name', 'like', "%{$query}%")
                  ->orWhere('event_permits.organizer_name', 'like', "%{$query}%")
                  ->orWhere('event_permits.application_number', 'like', "%{$query}%")
                  ->orWhere('event_permits.venue', 'like', "%{$query}%");
            })
            ->orderBy('event_permits.created_at', 'desc')
            ->paginate(20);

        return view('events.permits.search', compact('permits', 'query'));
    }

    public function rejected()
    {
        $permits = DB::table('event_permits')
            ->leftJoin('event_categories', 'event_permits.event_category_id', '=', 'event_categories.id')
            ->select('event_permits.*', 'event_categories.name as category_name')
            ->where('event_permits.status', 'rejected')
            ->orderBy('event_permits.created_at', 'desc')
            ->paginate(20);

        return view('events.permits.rejected', compact('permits'));
    }

    public function expired()
    {
        $permits = DB::table('event_permits')
            ->leftJoin('event_categories', 'event_permits.event_category_id', '=', 'event_categories.id')
            ->select('event_permits.*', 'event_categories.name as category_name')
            ->where('event_permits.status', 'expired')
            ->orWhere(function($query) {
                $query->where('event_permits.event_date', '<', now())
                      ->where('event_permits.status', '!=', 'completed');
            })
            ->orderBy('event_permits.created_at', 'desc')
            ->paginate(20);

        return view('events.permits.expired', compact('permits'));
    }

    public function categories()
    {
        $categories = DB::table('event_categories')
            ->orderBy('name')
            ->paginate(20);

        return view('events.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:event_categories,name',
            'description' => 'nullable|string',
            'fee_amount' => 'required|numeric|min:0',
        ]);

        DB::table('event_categories')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'fee_amount' => $request->fee_amount,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Event category created successfully.');
    }
}