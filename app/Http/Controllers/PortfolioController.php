<?php

namespace App\Http\Controllers;

use App\Models\PortfolioImage;
use App\Models\Specialty;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function index(): View
    {
        $photographer = auth()->user()->photographer;

        $images = $photographer->portfolioImages()
            ->with('specialty')
            ->ordered()
            ->get();

        $specialties = Specialty::all();

        return view('photographer.portfolio.index', compact('images', 'specialties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max
        ], [
            'images.required' => 'Veuillez sélectionner au moins une image.',
            'images.max' => 'Vous pouvez uploader maximum 10 images à la fois.',
            'images.*.image' => 'Le fichier doit être une image.',
            'images.*.mimes' => 'Format accepté : JPEG, PNG, JPG, WEBP.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5 Mo.',
        ]);

        $photographer = auth()->user()->photographer;

        // Check total limit (max 50 images)
        $currentCount = $photographer->portfolioImages()->count();
        $newCount = count($request->file('images'));

        if ($currentCount + $newCount > 50) {
            return back()->with('error', 'Vous ne pouvez pas avoir plus de 50 images dans votre portfolio.');
        }

        $maxSortOrder = $photographer->portfolioImages()->max('sort_order') ?? 0;

        foreach ($request->file('images') as $index => $image) {
            $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('portfolios/' . $photographer->id, $filename, 'public');

            PortfolioImage::create([
                'photographer_id' => $photographer->id,
                'filename' => $filename,
                'original_name' => $image->getClientOriginalName(),
                'path' => $path,
                'sort_order' => $maxSortOrder + $index + 1,
            ]);
        }

        return back()->with('success', count($request->file('images')) . ' image(s) ajoutée(s) avec succès.');
    }

    public function update(Request $request, PortfolioImage $portfolioImage): RedirectResponse
    {
        // Verify ownership
        if ($portfolioImage->photographer_id !== auth()->user()->photographer?->id) {
            abort(403);
        }

        $request->validate([
            'caption' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'is_featured' => ['boolean'],
        ]);

        $portfolioImage->update([
            'caption' => $request->caption,
            'specialty_id' => $request->specialty_id,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return back()->with('success', 'Image mise à jour.');
    }

    public function destroy(PortfolioImage $portfolioImage): RedirectResponse
    {
        // Verify ownership
        if ($portfolioImage->photographer_id !== auth()->user()->photographer?->id) {
            abort(403);
        }

        $portfolioImage->delete();

        return back()->with('success', 'Image supprimée.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:portfolio_images,id'],
        ]);

        $photographer = auth()->user()->photographer;

        foreach ($request->order as $position => $imageId) {
            PortfolioImage::where('id', $imageId)
                ->where('photographer_id', $photographer->id)
                ->update(['sort_order' => $position]);
        }

        return back()->with('success', 'Ordre mis à jour.');
    }
}
