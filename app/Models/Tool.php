<?php

namespace App\Models;

use Database\Factories\ToolFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'url', 'icon_path', 'sort_order'])]
class Tool extends Model
{
    /** @use HasFactory<ToolFactory> */
    use HasFactory;
}
