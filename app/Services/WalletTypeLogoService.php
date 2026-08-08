<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Stores wallet type logos on MinIO and returns their public URL.
 *
 * The URL is persisted on the wallet type itself rather than derived from its
 * name, so renaming a type does not orphan its logo — which is exactly what
 * happens with the legacy Cloudinary convention that slugifies the name.
 */
class WalletTypeLogoService
{
    private const DISK = 'minio';

    /**
     * Uploads a logo and returns its public URL.
     *
     * Filenames are randomised rather than derived from the wallet type name:
     * a stable name would let a re-upload be served from cache as the old
     * image, and would leak the naming scheme into the URL.
     */
    public function upload(TemporaryUploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
        $path = 'logos/' . Str::uuid()->toString() . '.' . $extension;

        Storage::disk(self::DISK)->put(
            $path,
            file_get_contents($file->getRealPath()),
            [
                'ContentType' => $file->getMimeType(),
                // Logos change rarely and are addressed by a unique name, so a
                // long cache lifetime is safe.
                'CacheControl' => 'public, max-age=31536000, immutable',
            ],
        );

        return Storage::disk(self::DISK)->url($path);
    }

    /**
     * Deletes a previously uploaded logo, ignoring anything not on our disk.
     *
     * Best-effort: a failure here must never block the master-data update that
     * triggered it, since the authoritative record lives in the wallet service.
     */
    public function deleteByUrl(?string $url): void
    {
        if (blank($url)) {
            return;
        }

        $base = rtrim(Storage::disk(self::DISK)->url(''), '/') . '/';

        if (! str_starts_with($url, $base)) {
            // A legacy Cloudinary URL, or a logo from another environment.
            return;
        }

        try {
            Storage::disk(self::DISK)->delete(substr($url, strlen($base)));
        } catch (\Throwable $e) {
            Log::warning('wallet_type_logo_delete_failed', [
                'service' => 'wallet_type_logo',
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
