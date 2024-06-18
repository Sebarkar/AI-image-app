<?php

namespace App\Http\Controllers\Front;

use App\Http\Resources\DatasetResource;
use App\Models\Datasets;
use App\Services\Files\FileStorage;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DatasetsController
{
    public function index(Request $request)
    {
        $datasets = Datasets::where('user_id', $request->user()->id)
            ->with('images')
            ->get();

        return response()->json(DatasetResource::collection($datasets));
    }

    public function create(Request $request)
    {
        if (count($request->files->all()) === 0) {
            return response()->json([
                'message' => 'No files uploaded',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'title' => Rule::unique('datasets')->where(fn (Builder $query) => $query->where('user_id', $request->user()->id)),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $dataset = Datasets::create([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
        ]);

        FileStorage::storeImages($request->files->all(), $dataset, 'datasets');
        FileStorage::storeZip($request->files->all(), $dataset, 'datasets');

        $dataset = $dataset->loadMissing('images', 'archive');

        return response()->json(new DatasetResource($dataset));
    }

    public function bulkRemove(Request $request)
    {
        $datasets = Datasets::whereIn('id', $request->json('ids'))->get();
        foreach ($datasets as $dataset) {
            $dataset->removeAllContent();
        }

        return response()->noContent();
    }
}
