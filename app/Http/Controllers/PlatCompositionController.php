<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Product;
use App\Models\TempPlat;
use App\Models\LignePlat;
use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PlatCompositionController extends Controller
{
    /**
     * Display a listing of plat compositions
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('Plats')) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder à cette page');
        }

        $countPlat = Plat::count();
        if ($countPlat == 0) {
            return view('Error.index')
                ->withErrors('Vous n\'avez pas de plats. Veuillez d\'abord créer des plats.');
        }

        $countProduct = Product::count();
        if ($countProduct == 0) {
            return view('Error.index')
                ->withErrors('Vous n\'avez pas de produits.');
        }

        if ($request->ajax()) {
            $Data_Plat = DB::table('plats as p')
                ->join('users as us', 'us.id', '=', 'p.iduser')
                ->join('ligne_plat as l', 'l.id_plat', '=', 'p.id')
                ->join('products as pro', 'pro.id', '=', 'l.idproduit')
                ->join('unite as u', 'u.id', '=', 'pro.id_unite')
                ->select(
                    'l.id',
                    'pro.name',
                    'p.name as nom_plat',
                    DB::raw("CONCAT(us.prenom, ' ', us.nom) as created_by"),
                    'l.created_at',
                    'l.qte','l.nombre_couvert',
                    'u.name as unite'
                )
                ->whereNull('l.deleted_at')
                ->orderBy('l.id', 'desc');

            return DataTables::of($Data_Plat)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('Plats-modifier')) {
                        $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 editPlatComposition"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-pen-to-square text-primary"></i>
                                </a>';
                    }

                    if (auth()->user()->can('Plats')) {
                        $btn .= '<a href="' . url('ShowPlatDetail/' . $row->id) . '" 
                                    class="btn btn-sm bg-success-subtle me-1" 
                                    target="_blank">
                                    <i class="fa-solid fa-eye text-success"></i>
                                </a>';
                    }

                    if (auth()->user()->can('Plats-supprimer')) {
                        $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle deletePlatComposition"
                                    data-id="' . $row->id . '">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $plats = Plat::all();
        $unites = Unite::all();

        return view('plat_composition.index')
            ->with('plats', $plats)
            ->with('unites', $unites);
    }

    /**
     * Get plats by type
     */
    public function getPlatsByTypeForComposition(Request $request)
    {
        $type = $request->type;
        $plats = Plat::where('type', $type)
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'status' => 200,
            'data' => $plats,
        ]);
    }

    /**
     * Search for products
     */
    public function getProductForPlat(Request $request)
    {
        $name_product = $request->product;

        if ($request->ajax()) {
            $Data_Product = DB::table('products as p')
                ->join('stock as s', 'p.id', '=', 's.id_product')
                ->join('locals as l', 'p.id_local', '=', 'l.id')
                ->join('unite as u', 'p.id_unite', '=', 'u.id')
                ->where('p.name', 'like', '%' . $name_product . '%')
                ->whereNull('p.deleted_at')
                ->select(
                    'p.name',
                    's.quantite',
                    'p.seuil',
                    'l.name as name_local',
                    'p.id',
                    'u.id as id_unite',
                    'u.name as unite_name'
                )
                ->get();

            return response()->json([
                'status' => 200,
                'data' => $Data_Product
            ]);
        }
    }

    /**
     * Store product in temporary table
     */
    public function PostInTmpPlat(Request $request)
    {
        if (!auth()->user()->can('Plats-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des compositions de plats'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'idproduit' => 'required|exists:products,id',
            'id_plat' => 'required|exists:plats,id',
            'id_unite' => 'required|exists:unite,id',
            'qte' => 'required|numeric|min:0.01',
            'nombre_couvert' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        $data = $request->all();
        $data['id_user'] = Auth::user()->id;

        DB::beginTransaction();

        try {
            $existingProduct = TempPlat::where('idproduit', $data['idproduit'])
                ->where('id_plat', $data['id_plat'])
                ->where('id_user', $data['id_user'])
                ->first();

            if ($existingProduct) {
                $existingProduct->qte += $data['qte'];
                $existingProduct->save();
                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Quantité mise à jour avec succès',
                ]);
            } else {
                TempPlat::create($data);
                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Produit ajouté avec succès',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue. Veuillez réessayer.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get temporary plat composition by plat ID
     */
    public function GetTmpPlatByPlatId(Request $request)
    {
        $Data = DB::table('temp_plat as t')
            ->join('plats as pl', 't.id_plat', '=', 'pl.id')
            ->join('products as p', 't.idproduit', '=', 'p.id')
            ->join('unite as u', 't.id_unite', '=', 'u.id')
            ->join('users as us', 't.id_user', '=', 'us.id')
            ->where('t.id_plat', '=', $request->id_plat)
            ->where('t.id_user', '=', Auth::user()->id)
            ->whereNull('p.deleted_at')
            ->select(
                't.id',
                'p.name as product_name',
                'pl.name as plat_name',
                't.qte',
                'u.name as unite_name',
                't.nombre_couvert'
            );

        return DataTables::of($Data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';

                $btn .= '<a href="#" class="btn btn-sm bg-primary-subtle me-1 EditTmpPlat"
                            data-id="' . $row->id . '">
                            <i class="fa-solid fa-pen-to-square text-primary"></i>
                        </a>';

                $btn .= '<a href="#" class="btn btn-sm bg-danger-subtle DeleteTmpPlat"
                            data-id="' . $row->id . '">
                            <i class="fa-solid fa-trash text-danger"></i>
                        </a>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store plat composition
     */
    public function StorePlatComposition(Request $request)
    {
        if (!auth()->user()->can('Plats-ajoute')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission d\'ajouter des compositions de plats'
            ], 403);
        }

        $userId = Auth::id();
        $platId = $request->id_plat;

        // Retrieve temporary plat data
        $TempPlat = TempPlat::where('id_user', $userId)
            ->where('id_plat', $platId)
            ->get();

        if ($TempPlat->isEmpty()) {
            return response()->json([
                'status' => 400,
                'message' => 'Aucun produit trouvé pour ce plat'
            ]);
        }

        DB::beginTransaction();

        try {
            // Insert plat composition details
            foreach ($TempPlat as $item) {
                LignePlat::create([
                    'id_user' => $userId,
                    'id_plat' => $item->id_plat,
                    'idproduit' => $item->idproduit,
                    'id_unite' => $item->id_unite,
                    'qte' => $item->qte,
                    'nombre_couvert' => $item->nombre_couvert,
                ]);
            }

            // Delete temporary plat records
            TempPlat::where('id_user', $userId)
                ->where('id_plat', $platId)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Composition de plat ajoutée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de l\'enregistrement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update quantity in temp table
     */
    public function UpdateQteTmpPlat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qte' => 'required|numeric|min:0.01',
            'nombre_couvert' => 'required|integer|min:1',
        ], [
            'required' => 'Le champ :attribute est requis.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'min' => 'Le champ :attribute doit être supérieur à :min.',
        ], [
            'qte' => 'quantité',
            'nombre_couvert' => 'nombre de couverts',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ], 400);
        }

        $TempPlat = TempPlat::where('id', $request->id)->update([
            'qte' => $request->qte,
            'nombre_couvert' => $request->nombre_couvert,
        ]);

        if ($TempPlat) {
            return response()->json([
                'status' => 200,
                'message' => 'Mise à jour effectuée avec succès.'
            ]);
        }
    }

    /**
     * Delete row from temp table
     */
    public function DeleteRowsTmpPlat(Request $request)
    {
        $TempPlat = TempPlat::where('id', $request->id)->delete();

        if ($TempPlat) {
            return response()->json([
                'status' => 200,
                'message' => 'Suppression effectuée avec succès.'
            ]);
        }
    }

    /**
     * Edit plat composition
     */
    public function edit($id)
    {
        if (!auth()->user()->can('Plats-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier'
            ], 403);
        }

        $plat = Plat::find($id);

        if (!$plat) {
            return response()->json([
                'status' => 404,
                'message' => 'Plat non trouvé'
            ], 404);
        }

        // Get all ligne_plat for this plat and load into temp_plat
        $lignePlats = LignePlat::where('id_plat', $id)->get();

        // Clear existing temp data for this user and plat
        TempPlat::where('id_user', Auth::id())
            ->where('id_plat', $id)
            ->delete();

        // Load data into temp table
        foreach ($lignePlats as $ligne) {
            TempPlat::create([
                'id_user' => Auth::id(),
                'id_plat' => $ligne->id_plat,
                'idproduit' => $ligne->idproduit,
                'id_unite' => $ligne->id_unite,
                'qte' => $ligne->qte,
                'nombre_couvert' => $ligne->nombre_couvert,
            ]);
        }

        return response()->json([
            'status' => 200,
            'plat' => $plat
        ]);
    }

    /**
     * Update plat composition
     */
    public function update(Request $request)
    {
        if (!auth()->user()->can('Plats-modifier')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de modifier'
            ], 403);
        }

        $platId = $request->id_plat;
        $userId = Auth::id();

        $plat = Plat::find($platId);

        if (!$plat) {
            return response()->json([
                'status' => 404,
                'message' => 'Plat non trouvé'
            ], 404);
        }

        // Get temp data
        $TempPlat = TempPlat::where('id_user', $userId)
            ->where('id_plat', $platId)
            ->get();

        if ($TempPlat->isEmpty()) {
            return response()->json([
                'status' => 400,
                'message' => 'Aucun produit trouvé pour ce plat'
            ]);
        }

        DB::beginTransaction();

        try {
            // Delete existing ligne_plat records
            LignePlat::where('id_plat', $platId)->delete();

            // Insert new records from temp
            foreach ($TempPlat as $item) {
                LignePlat::create([
                    'id_user' => $userId,
                    'id_plat' => $item->id_plat,
                    'idproduit' => $item->idproduit,
                    'id_unite' => $item->id_unite,
                    'qte' => $item->qte,
                    'nombre_couvert' => $item->nombre_couvert,
                ]);
            }

            // Clear temp table
            TempPlat::where('id_user', $userId)
                ->where('id_plat', $platId)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Composition de plat mise à jour avec succès',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Erreur lors de la mise à jour',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete plat composition
     */
    public function destroy(Request $request)
    {
        if (!auth()->user()->can('Plats-supprimer')) {
            return response()->json([
                'status' => 403,
                'message' => 'Vous n\'avez pas la permission de supprimer'
            ], 403);
        }

        $platId = $request->id;

        DB::beginTransaction();

        try {
            // Delete ligne_plat records
            LignePlat::where('id_plat', $platId)->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Composition de plat supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Une erreur est survenue lors de la suppression'
            ], 500);
        }
    }

    /**
     * Show plat detail
     */
    public function ShowPlatDetail($id)
    {
        if (!auth()->user()->can('Plats')) {
            abort(403, 'Vous n\'avez pas la permission de voir ce détail');
        }

        $plat = Plat::findOrFail($id);

        $Data_LignePlat = DB::table('ligne_plat as l')
            ->join('products as p', 'l.idproduit', '=', 'p.id')
            ->join('unite as u', 'l.id_unite', '=', 'u.id')
            ->whereNull('p.deleted_at')
            ->select('p.name', 'l.qte', 'u.name as unite_name', 'l.nombre_couvert')
            ->where('l.id_plat', $id)
            ->get();

        return view('plat_composition.detail', compact('plat', 'Data_LignePlat'));
    }
}