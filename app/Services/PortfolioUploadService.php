<?php

namespace App\Services;

use App\Models\Photographer;
use App\Models\PortfolioImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class PortfolioUploadService
{
    public const MAX_IMAGES_PER_PORTFOLIO = 50;

    public const MAX_IMAGES_PER_UPLOAD = 10;

    public function uploadImages(Photographer $photographer, array $files): Collection
    {
        $maxSortOrder = $photographer->portfolioImages()->max('sort_order') ?? 0;
        $uploadedImages = collect();

        foreach ($files as $index => $file) {
            $image = $this->uploadSingleImage($photographer, $file, $maxSortOrder + $index + 1);
            $uploadedImages->push($image);
        }

        return $uploadedImages;
    }

    public function uploadSingleImage(Photographer $photographer, UploadedFile $file, int $sortOrder): PortfolioImage
    {
        $filename = uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('portfolios/'.$photographer->id, $filename, 's3');

        return PortfolioImage::create([
            'photographer_id' => $photographer->id,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'sort_order' => $sortOrder,
        ]);
    }

    public function canUpload(Photographer $photographer, int $newImageCount): array
    {
        $errors = [];
        $currentCount = $photographer->portfolioImages()->count();

        if ($currentCount + $newImageCount > self::MAX_IMAGES_PER_PORTFOLIO) {
            $remaining = self::MAX_IMAGES_PER_PORTFOLIO - $currentCount;
            $errors[] = 'Vous ne pouvez pas avoir plus de '.self::MAX_IMAGES_PER_PORTFOLIO." images dans votre portfolio. Il vous reste {$remaining} emplacement(s).";
        }

        return $errors;
    }

    public function getRemainingSlots(Photographer $photographer): int
    {
        $currentCount = $photographer->portfolioImages()->count();

        return max(0, self::MAX_IMAGES_PER_PORTFOLIO - $currentCount);
    }
}
