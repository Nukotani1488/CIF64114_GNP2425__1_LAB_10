<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\User;

class Note extends Model
{
    /**
     * The table associated with the model.
     * @var string
     */
    use HasFactory;
    protected $table = 'notes';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid');
    }

    public function updateContent(string $content): void
    {
        $this->content = $content;
        $this->save();
    }
}
