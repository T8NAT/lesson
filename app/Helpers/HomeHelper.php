<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Section;
use App\Models\Setting;

function settings()
{
    return Setting::query()->first();
}
//function categories()
//{
//    return  Category::query()->latest()->limit(5)->get();
//}


