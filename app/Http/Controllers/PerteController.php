<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pertes;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SubCategory; 
use App\Models\Category;
use App\Models\Product;
use App\Models\Unite;
use Illuminate\Support\Facades\DB;
class PerteController extends Controller
{
    /**
     * Display the pertes management page
     */
    public function index(Request $request)
    {
        $SubCategory = SubCategory::all();
        
        $Product = Product::all();
        $Unite = Unite::all();
        $classes = Category::select('classe')->distinct()->get();


        return view('pertes.pertes')
        ->with('SubCategory',$SubCategory)
        ->with('Category',$Category)
        ->with('Product',$Product)
        ->with('Unite',$Unite)
        ->with('classes',$classes);

    }
    public function getUniteByProduct(Request $request)
    {
        $unite_by_product = DB::select("select * from products where id = ?",[$request->id]);
         return response()->json([
            'status' =>200,
            'data' => $unite_by_product
         ]);
    }


    public function getcategory(Request $request)
    {
        $categorys = Category::where('classe',$request->classe)->get();
        return response()->json([
        'status' =>200,
        'data' => $unite_by_product
        ]);
    }
   
    


    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($request->all(), [
           
            'id_category' => 'required',
            'id_sub_categories' => 'required',
            'class' => 'required',
            'id_product' => 'required',
            'id_unite' => 'required',
            'nature' => 'required',
            'qte' => 'required',
            'date' => 'required',
            'cause' => 'required',
            
        ], [
            'required' => 'Le champ :attribute est requis.',
            
        ], [
            'id_category' => 'catégorie',
            'id_sub_categories' => 'famille',
            'id_product' => 'desgination',
            'id_unite' => 'unite',
            'qte' => 'Quantité',
            
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        $Pertes = Pertes::create($data);
        if($data)
        {
             return response()->json([
                'status' => 200,
                'errors' => $validator->messages(),
            ], 400);
        }


    }
}