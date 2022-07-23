<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/commandes",
     *     operationId="commande_list",
     *     tags={"commandes"},
     *     summary="liste des commandes",
     *     description="Obtenir la liste de toutes les commandes",
     *     @OA\Response(response="200", description="Afficher la liste de toutes les commandes")
     * )
     */
    public function index()
    {
        $commandes = Commande::all();
        return response()->json([
            'commandes' => $commandes,
            'staus' => 200
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Post(
     *      path="/api/commandes",
     *      operationId="commandes",
     *      tags={"commandes"},
     *      summary="Créer une commande",
     *      description="Création d'une commande",
     *      @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="model",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="mesure",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="delais",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="reste",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="client_id",
     *                     type="string"
     *                 ),
     *                 example={"model":"Jupe et robe", "description": "Je decrips le model","mesure":"lien de l'image","delais":"2022-02-02","total":5000, "reste":2000, "user_id":1,"client_id":1}
     *             )
     *         )
     *
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Exception"
     *      )
     * )
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'model' => 'required',
                'mesure' => 'required',
                'delais' => 'required',
                'total' => 'required',
                'reste' => 'required',
                'user_id' => 'required',
                'client_id' => 'required',
            ]);
            $commande = Commande::create([
                'numero' => 'N°'.date('d-m').random_int(1, 200),
                'model' => $request->model,
                'description' => $request->description,
                'mesure' => $request->mesure,
                'delais' => $request->delais,
                'total' => $request->total,
                'reste' => $request->reste,
                'user_id' => $request->user_id,
                'client_id' => $request->client_id
            ]);
            if ($commande) {
                Log::info("Création d'une commande réussie: $request->numero -- $request->model -- $request->description -- $request->mesure -- $request->delais -- $request->total -- $request->reste -- $request->user_id -- $request->client_id ".now());
                return response()->json([
                    'message' => 'Commande créé avec succès',
                    'commande' => $commande,
                    'status' => 201
                ], 201);
            }else{
                Log::warning("Création d'une commande est impossible: $request->numero -- $request->model -- $request->description -- $request->mesure -- $request->delais -- $request->total -- $request->reste -- $request->user_id -- $request->client_id ".now());
                return response()->json([
                    'message' => 'La création d\'une comande a échoué',
                    'status' => 403
                ], 403);
            }
        } catch (Exception $exception) {
            Log::critical("Création d'une commande impossible, Exception $exception ".now());
            return response()->json([
                'Exception' => $exception,
                'status' => 405
            ], 405);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/commandes/2",
     *     operationId="one_commande",
     *     tags={"commandes"},
     *     summary="Obtenir une seul commande",
     *     description="Obtenir une seul commande",
     *     @OA\Response(
     *          response="200",
     *          description="Afficher une seul commande"
     *         ),
     *     @OA\Response(
     *          response="404",
     *          description="commande intouvable ou inexistante"
     *         ),
     * )
     */
    public function show($id)
    {
        $commande = Commande::find($id);
        if(!$commande){
            return response()->json([
                'message' => 'Commande introuvable ou inexistante',
                'status' => 404
            ],404);
        }
        return response()->json([
            'commande' => $commande,
            'status' => 200
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
