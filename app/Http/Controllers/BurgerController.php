<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BurgerController extends Controller
{
    // Catalogue public (client)
    public function catalogue(Request $request)
    {
        $query = Burger::with('categorie')->where('archive', false);

        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        $burgers    = $query->paginate(10);
        $categories = Categorie::all();

        return view('burgers.catalogue', compact('burgers', 'categories'));
    }

    public function show(Burger $burger)
    {
        return view('burgers.show', compact('burger'));
    }

    // CRUD gestionnaire
    public function index()
    {
        $burgers = Burger::with('categorie')->latest()->paginate(10);
        return view('gestionnaire.burgers.index', compact('burgers'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('gestionnaire.burgers.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock'        => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('burgers', 'public');
        }

        Burger::create($data);

        return redirect()->route('gestionnaire.burgers.index')
            ->with('success', 'Burger ajouté avec succès.');
    }

    public function edit(Burger $burger)
    {
        $categories = Categorie::all();
        return view('gestionnaire.burgers.edit', compact('burger', 'categories'));
    }

    public function update(Request $request, Burger $burger)
    {
        $data = $request->validate([
            'nom'          => 'required|string|max:255',
            'prix'         => 'required|numeric|min:0',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stock'        => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            if ($burger->image) {
                Storage::disk('public')->delete($burger->image);
            }
            $data['image'] = $request->file('image')->store('burgers', 'public');
        }

        $burger->update($data);

        return redirect()->route('gestionnaire.burgers.index')
            ->with('success', 'Burger modifié avec succès.');
    }

    public function archiver(Burger $burger)
    {
        $burger->archiver();
        return back()->with('success', 'Burger archivé.');
    }

    public function destroy(Burger $burger)
    {
        if ($burger->image) {
            Storage::disk('public')->delete($burger->image);
        }
        $burger->delete();
        return redirect()->route('gestionnaire.burgers.index')
            ->with('success', 'Burger supprimé.');
    }
}
