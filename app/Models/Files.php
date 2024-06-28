<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Files extends Model
{
    public $table = 'files';

    protected $fillable = [
        'name',
        'target_id',
        'filename',
        'storage_path',
        'storage',
        'target',
        'hash',
        'extension',
    ];

    public function task()
    {
        return $this->belongsTo(Image::class, 'target_id', 'id')->where('target', 'tasks');
    }

    public function getSrc()
    {
        return Storage::disk($this->storage)->url($this->storage_path);
    }

    public function remove()
    {
        Storage::disk($this->storage)->delete($this->storage_path);
        $this->delete();
    }

}
