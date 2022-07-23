<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/clients",
     *     operationId="client_list",
     *     tags={"clients"},
     *     summary="list des clients",
     *     description="Obtenir la liste de tous les clients",
     *     @OA\Response(response="200", description="Afficher la liste de tous les clients")
     * )
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json([
            'clients' => $clients,
            'status' => 200
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
     *      path="/api/clients",
     *      operationId="clients",
     *      tags={"clients"},
     *      summary="Créer un client",
     *      description="Création d'un client",
     *      @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nom",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sexe",
     *                     type="string"
     *                 ),
     *                 example={"nom": "Kouadio","phone":"0102030405", "image": "lien de l'image","sexe":"h"}
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
            $this->validate($request,[
                'nom' =>'required',
                'phone' =>'required',
                'sexe' =>'required',
            ]);
            $client = Client::create([
                'nom' => $request->nom,
                'phone' => $request->phone,
                'image' => $request->image,
                'sexe' => $request->sexe,
            ], 201);
            if ($client) {
                Log::info("Création d'un client réussie: $request->nom -- $request->phone -- $request->image ".now());
                return response()->json([
                    'message' => 'Client créé avec succès',
                    'client' => $client,
                    'status' => 201
                ], 201);
            }else{
                Log::warning("Création d'un client est impossible: $request->nom -- $request->phone -- $request->image ".now());
                return response()->json([
                    'message' => 'La création d\'un client a échoué',
                    'status' => 403
                ], 403);
            }
        } catch (Exception $exception) {
            Log::critical("Création d'un client est impossible, Exception: $exception");
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
     *     path="/api/clients/{id}",
     *     operationId="one_client",
     *     tags={"clients"},
     *     summary="Obtenir un seul client",
     *     description="Obtenir un seul client",
     *     @OA\Response(
     *          response="200",
     *          description="Afficher un seul client"
     *         ),
     *     @OA\Response(
     *          response="404",
     *          description="Client intouvable ou inexistant"
     *         ),
     * )
     */
    public function show($id)
    {
        $client = Client::find($id);
        if(!$client){
            return response()->json([
                'message' => 'Client introuvable ou inexistant',
                'status' => 404
            ], 404);
        }
        return response()->json([
            'client' => $client,
            'status' => 200
        ], 200);
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
    /**
     * @OA\Put(
     *      path="/api/clients/{id}",
     *      operationId="update_clients",
     *      tags={"clients"},
     *      summary="Mise à jour d'un client",
     *      description="Mise à jour des données d'un client",
     *      @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nom",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="sexe",
     *                     type="string"
     *                 ),
     *                 example={"nom": "Kouadio","phone":"0102030405","image":"lien modifié","sexe":"f"}
     *             )
     *         )
     *
     *      ),
     *      @OA\Response(
     *          response=200,
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
     *          response=404,
     *          description="Not Found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Exception"
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'nom' => 'required',
                'phone' => 'required',
                'sexe' => 'required'
            ]);
            $client = Client::find($id);
            if(!$client){
                return response()->json([
                    'message' => 'Client introuvable ou inexistant',
                    'status' => 404
                ], 404);
            }
            $result = $client->update([
                'nom' => $request->nom,
                'phone' => $request->phone,
                'image' => $request->image,
                'sexe' => $request->sexe,
            ]);
            if($result){
                Log::info("Client mise à jour avec succès: $request->nom -- $request->phone -- $request->image ".now());
                return response()->json([
                    'message' => 'Client modifié avec succès',
                    'client' => $client,
                    'status' => 200
                ], 200);
            }else{
                Log::warning("Mise à jour d'un client est impossible: $request->nom -- $request->phone -- $request->image ".now());
                return response()->json([
                    'message' => 'Mise à jour d\'un client a échoué',
                    'status' => 403
                ], 403);
            }
        } catch (Exception $exception) {
            Log::warning("Mise à jour d'un client est impossible, Exception : $exception ".now());
            return response()->json([
                'Exception' => $exception,
                'status' => 405
            ], 405);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     operationId="delete_client",
     *     tags={"clients"},
     *     summary="Supprimer un client",
     *     description="Supprimer un client",
     *     @OA\Response(
     *          response="200",
     *          description="Supprime un seul client"
     *         ),
     *     @OA\Response(
     *          response="404",
     *          description="client intouvable ou inexistant"
     *         ),
     * )
     */
    public function destroy($id)
    {
        $client = Client::find($id);
            if(!$client){
                return response()->json([
                    'message' => 'Client introuvable ou inexistant',
                    'status' => 404
                ], 404);
            }
            if($client->delete()) {
                return response()->json([
                    'message' => 'Client supprimé avec succès',
                    'status' => 200
                ], 200);
            }
    }
}
