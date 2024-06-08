<?php

namespace App\Http\Controllers;

use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchemesController extends Controller
{
    public function index()
    {
        $schemes = Scheme::orderBy('disability')->get();
        $types = Scheme::select('disability', DB::raw('COUNT(*) as count'))
            ->groupBy('disability')
            ->get();

        return view('schemes.index', compact(['schemes', 'types']));
    }

    public function add()
    {
        return view('schemes.add');
    }

    public function register(Request $request)
    {
        $request = $request->validate([
            'name' => 'required',
            'disability' => 'required',
            'description' => 'required',
            'how_to_apply' => 'required'
        ]);

        if (str_starts_with($request['how_to_apply'], "https")) {
            $request['link'] = $request['how_to_apply'];
            $request['how_to_apply'] = "<a href='"
                . $request['link']
                . "' target='_blank' class='text-decoration-none text-white btn btn-sm'>"
                . "Click Here" . "</a>";
        }

        Scheme::create($request);

        return redirect('/schemes')->with('success', "Scheme added successfully");
    }

    public function edit(int $id)
    {
        $scheme = Scheme::findOrFail($id);

        return view('schemes.edit', compact('scheme'));
    }

    public function update(int $id, Request $request)
    {
        $scheme = Scheme::findOrFail($id);

        $request = $request->validate([
            'name' => 'required',
            'disability' => 'required',
            'description' => 'required',
            'how_to_apply' => 'required'
        ]);

        if (str_starts_with($request['how_to_apply'], "https")) {
            $request['link'] = $request['how_to_apply'];
            $request['how_to_apply'] = "<a href='"
                . $request['link']
                . "' target='_blank' class='text-decoration-none text-white btn btn-sm'>"
                . "Click Here" . "</a>";
        }

        $scheme->update($request);

        return redirect('/schemes')->with('success', "Scheme updated successfully");
    }

    public function delete(int $id)
    {
        $scheme = Scheme::find($id);
        $scheme->delete();

        return redirect('/schemes')->with('success', "Scheme deleted successfully");
    }

    public function search(Request $request)
    {
        if ($request["disability"] === "All") {
            $schemes = Scheme::orderBy('disability')->get();
        } else {
            $schemes = Scheme::where('disability', 'like', '%' . $request["disability"] . '%')
                ->orderBy('name')
                ->get();
        }

        return response()->json($schemes, 200);
    }
}
