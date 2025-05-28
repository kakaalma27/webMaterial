<?php

namespace App\Http\Controllers;


use App\Models\Plumbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlumbingController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();

        $query = Plumbing::query()->where('user_id', $user_id); // Filter berdasarkan user yang login

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $Plumbing = $query->paginate(10)->withQueryString();

        return view('main.plumbing.index', compact('Plumbing'));
    }

    public function create()
    {
        return view('plumbings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'status' => 'required|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('image')->store('Plumbing', 'public');
        $user_id = auth()->id();
        Plumbing::create([
            'user_id' => $user_id,
            'name' => $validated['name'],
            'stock' => $validated['stock'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'path' => $path,
        ]);

        return redirect()->route('plumbings.index')->with('success', 'Plumbing created successfully.');
    }

    public function show($id)
    {
        $Plumbing = Plumbing::findOrFail($id);
        return view('plumbings.show', compact('Plumbing'));
    }

    public function edit($id)
    {
        $Plumbing = Plumbing::findOrFail($id);
        return view('plumbings.edit', compact('Plumbing'));
    }

    public function update(Request $request, $id)
    {
        $Plumbing = Plumbing::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($Plumbing->path && Storage::disk('public')->exists($Plumbing->path)) {
                Storage::disk('public')->delete($Plumbing->path);
            }
            $validated['path'] = $request->file('image')->store('Plumbing', 'public');
        }

        $Plumbing->update($validated);

        return redirect()->route('plumbings.index')->with('success', 'Plumbing updated successfully.');
    }

    public function destroy($id)
    {
        $Plumbing = Plumbing::findOrFail($id);
        if ($Plumbing->path && Storage::disk('public')->exists($Plumbing->path)) {
            Storage::disk('public')->delete($Plumbing->path);
        }
        $Plumbing->delete();

        return redirect()->route('plumbings.index')->with('success', 'Plumbing deleted successfully.');
    }
}
