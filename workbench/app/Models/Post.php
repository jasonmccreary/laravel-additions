<?php

namespace Workbench\App\Models;

use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JMac\Additions\Traits\FindBy;
use Workbench\Database\Factories\PostFactory;

#[UseFactory(PostFactory::class)]
class Post extends Model
{
    use FindBy, HasFactory;
}
