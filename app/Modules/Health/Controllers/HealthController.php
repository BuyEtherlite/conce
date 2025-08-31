<?php

namespace App\Modules\Health\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index()
    {
        return view('health/health.index');
    }

    public function create()
    {
        return view('health/health.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Created successfully');
    }

    public function show($id)
    {
        return view('health/health.show', compact('id'));
    }

    public function edit($id)
    {
        return view('health/health.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function destroy($id)
    {
        // Implementation needed
        return redirect()->back()->with('success', 'Deleted successfully');
    }
}
<?php

namespace App\Modules\Health\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index()
    {
        return view('health.index');
    }

    public function facilities()
    {
        return view('health.facilities.index');
    }

    public function inspections()
    {
        return view('health.inspections.index');
    }

    public function permits()
    {
        return view('health.permits.index');
    }
}
