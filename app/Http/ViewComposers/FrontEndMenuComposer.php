<?php
namespace App\Http\ViewComposers;
use App\Http\Controllers\Admin\ProductCatalogCategoriesController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use phpDocumentor\Reflection\Types\Null_;


class FrontEndMenuComposer{
    public function createMenu(View $view)
    {
        $catController = new ProductCatalogCategoriesController();
        $totalString = $catController->categoryListMenu();
        $view->with(['totalString'=>$totalString]);
    }
}
