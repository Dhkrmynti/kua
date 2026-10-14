<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['submission_id', 'document_type', 'file_path'])]
class SubmissionDocument extends Model
{
    use HasFactory;

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
