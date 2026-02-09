<?php

namespace App\Observers;

use App\Models\PortfolioImage;
use Illuminate\Support\Facades\Storage;

class PortfolioImageObserver
{
    public function deleting(PortfolioImage $image): void
    {
        if ($image->path) {
            Storage::disk('s3')->delete($image->path);
        }
        if ($image->thumbnail_path) {
            Storage::disk('s3')->delete($image->thumbnail_path);
        }
    }
}
