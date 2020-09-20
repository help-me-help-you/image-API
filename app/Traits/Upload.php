<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait Upload
{
	public function uploadOne($imageDataDecoded, $folder = null, $imageName)
	{
		$file = Storage::disk('images')->put("/{$folder}/" . $imageName, $imageDataDecoded);

		return $file;
	}
}
