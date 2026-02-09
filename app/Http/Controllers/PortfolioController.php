<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReorderPortfolioImagesRequest;
use App\Http\Requests\StorePortfolioImagesRequest;
use App\Http\Requests\UpdatePortfolioImageRequest;
use App\Models\PortfolioImage;
use App\Models\Specialty;
use App\Services\PortfolioUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    public function __construct(
        private PortfolioUploadService $uploadService
    ) {}

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

    public function store(StorePortfolioImagesRequest $request): RedirectResponse
    {
        $photographer = auth()->user()->photographer;
        $files = $request->file('images');

        $errors = $this->uploadService->canUpload($photographer, count($files));
        if (! empty($errors)) {
            return back()->with('error', $errors[0]);
        }

        $uploadedImages = $this->uploadService->uploadImages($photographer, $files);

        return back()->with('success', $uploadedImages->count().' image(s) ajoutée(s) avec succès.');
    }

    public function update(UpdatePortfolioImageRequest $request, PortfolioImage $portfolioImage): RedirectResponse
    {
        $portfolioImage->update([
            'caption' => $request->caption,
            'specialty_id' => $request->specialty_id,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return back()->with('success', 'Image mise à jour.');
    }

    public function destroy(PortfolioImage $portfolioImage): RedirectResponse
    {
        if ($portfolioImage->photographer_id !== auth()->user()->photographer?->id) {
            abort(403);
        }

        $portfolioImage->delete();

        return back()->with('success', 'Image supprimée.');
    }

    public function reorder(ReorderPortfolioImagesRequest $request): RedirectResponse
    {
        $photographer = auth()->user()->photographer;

        foreach ($request->order as $position => $imageId) {
            PortfolioImage::where('id', $imageId)
                ->where('photographer_id', $photographer->id)
                ->update(['sort_order' => $position]);
        }

        return back()->with('success', 'Ordre mis à jour.');
    }
}
