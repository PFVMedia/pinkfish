<?php

namespace App\Models;

use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'url', 'description', 'category', 'sort_order'])]
class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;
}
