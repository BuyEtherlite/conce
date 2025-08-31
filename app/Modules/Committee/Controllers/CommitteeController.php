<?php

namespace App\Modules\Committee\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Committee\Models\Committee;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with(['chairperson', 'secretary'])->get();
        return view('committee.index', compact('committees'));
    }

    public function create()
    {
        return view('committee.committees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'committee_type' => 'required|string',
            'meeting_frequency' => 'required|string',
            'chairperson_id' => 'nullable|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id',
            'established_date' => 'required|date',
        ]);

        Committee::create($request->all());

        return redirect()->route('committee.index')->with('success', 'Committee created successfully.');
    }

    public function show(Committee $committee)
    {
        return view('committee.committees.show', compact('committee'));
    }

    public function edit(Committee $committee)
    {
        return view('committee.committees.edit', compact('committee'));
    }

    public function update(Request $request, Committee $committee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'committee_type' => 'required|string',
            'meeting_frequency' => 'required|string',
            'chairperson_id' => 'nullable|exists:users,id',
            'secretary_id' => 'nullable|exists:users,id',
            'established_date' => 'required|date',
        ]);

        $committee->update($request->all());

        return redirect()->route('committee.index')->with('success', 'Committee updated successfully.');
    }

    public function destroy(Committee $committee)
    {
        $committee->delete();
        return redirect()->route('committee.index')->with('success', 'Committee deleted successfully.');
    }

    public function committees()
    {
        return view('committee.committees.index');
    }

    public function members()
    {
        return view('committee.members.index');
    }

    public function meetings()
    {
        return view('committee.meetings.index');
    }

    public function agendas()
    {
        return view('committee.agendas.index');
    }

    public function minutes()
    {
        return view('committee.minutes.index');
    }

    public function resolutions()
    {
        return view('committee.resolutions.index');
    }
}