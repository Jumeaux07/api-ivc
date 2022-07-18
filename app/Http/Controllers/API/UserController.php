<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/login_user",
     *      operationId="login_user",
     *      tags={"utilisateurs"},
     *      summary="Connexion",
     *      description="Connexion d'un utilisateur",
     *      @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"phone": "0505206604", "password": "12345678X"}
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
     *          response=404,
     *          description="Aucun compte n'est associé à ce numéro"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Exception"
     *      )
     * )
     */
    public function login_user(Request $request){
        $this->validate($request,[
            'phone' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('phone', $request->phone)->first();
        if(!$user){
            return response()->json([
                'message' => 'Aucun compte n\'est associé à ce numéro',
                'status' => 404
            ], 404);
        }
        if(Hash::check($request->password, $user->password) == true){
            Log::infoo("Un utilisateur s'est connecté: $user->nom -- $user->prenoms - $user->nom_atelier ".now());
            return response()->json([
                'message' => 'Connexion réussie',
                'status' => 200,
                'user' => $user,
                'token' => $user->createToken($user->nom.''.$user->created_at)->plainTextToken,
            ], 200);
        }else{
            Log::warning("Connexion d'un utilisateur est impossible: ['phone' => $request->phone] ".now());
            return response()->json([
                'message' => 'Mot de passe incorrecte',
                'status' => 400
            ], 400);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * @OA\Get(
     *     path="/api/users",
     *     operationId="user_list",
     *     tags={"utilisateurs"},
     *     summary="list des utilisateurs",
     *     description="Obtenir la liste de tous les utilisateurs",
     *     @OA\Response(response="200", description="Afficher la liste de tous les utilisateurs")
     * )
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
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
     *      path="/api/users",
     *      operationId="users",
     *      tags={"utilisateurs"},
     *      summary="Inscription",
     *      description="Création d'un utilisateur",
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
     *                     property="prenoms",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="nom_atelier",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 example={"nom": "Kouadio","prenoms":"Hervé", "phone":"0102030405", "email": "kouadio@gmail.com","nom_atelier":"KBCouture", "password": "12345678X", "password_confirmation": "12345678X"}
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
                'nom' => 'required',
                'prenoms' => 'required',
                'phone' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
            ]);
            $user = User::create([
                'nom' => $request->nom,
                'prenoms' => $request->prenoms,
                'phone' => $request->phone,
                'email' => $request->email,
                'nom_atelier' => $request->nom_atelier,
                'password' => Hash::make($request->password),
            ]);
            if ($user) {
                Log::info("Un utilisateur s'est inscrit avec succès: $request->nom -- $request->prenoms -- $request->phone -- $request->email -- $request->nom_atelier ".now());
                return response()->json([
                    'message' => 'Utilisateur créé avec succès',
                    'user' => $user,
                    'status' => 201
                ], 201);
            } else {
                Log::warning("Inscription d'un utilisateur est impossible: $request->nom -- $request->prenoms -- $request->phone -- $request->email -- $request->nom_atelier ".now());
                return response()->json([
                    'message' => 'Utilisateur non créé',
                    'status' => 403
                ], 403);
            }
        } catch (Exception $exception) {
            Log::critical("Inscription d'un utilisateur est impossible, Exception $exception ".now());
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
     *     path="/api/users/{id}",
     *     operationId="one_user",
     *     tags={"utilisateurs"},
     *     summary="Obtenir un seul utilisateur",
     *     description="Obtenir un seul utilisateur",
     *     @OA\Response(
     *          response="200",
     *          description="Afficher un seul utilisateur"
     *         ),
     *     @OA\Response(
     *          response="404",
     *          description="Utilisateur intouvable ou inexistant"
     *         ),
     * )
     */
    public function show($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'Utilisateur introuvable ou inexistant',
                'status' => 404
            ], 404);
        }
        return response()->json([
            'user' => $user,
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
     *      path="/api/users/{id}",
     *      operationId="update_users",
     *      tags={"utilisateurs"},
     *      summary="Mise à jour d'un utilisateur",
     *      description="Mise à jour des données d'un utilisateur",
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
     *                     property="prenoms",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="nom_atelier",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 example={"nom": "Kouadio","prenoms":"Hervé", "phone":"0102030405","nom_atelier":"KBCouture"}
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
        try{
            $this->validate($request,[
                'nom' => 'required',
                'prenoms' => 'required',
                'phone' => 'required',
            ]);
            $user = User::find($id);
            if(!$user){
                return response()->json([
                    'message' => 'Utilisateur introuvable ou inexistant',
                    'status' => 404
                ], 404);
            }
            $result = $user->update([
                'nom' => $request->nom,
                'prenoms' => $request->prenoms,
                'phone' => $request->phone,
                'nom_atelier' => $request->nom_atelier
            ]);
            if($result){
                Log::info("Mise à jour des données d'un utilisateur réussie: $request->nom -- $request->prenoms -- $request->phone -- $request->email -- $request->nom_atelier ".now());
                return response()->json([
                    'message' => 'Utilisateur modifié avec succès',
                    'user' => $user,
                    'status' => 200
                ], 200);
            }else{
                Log::warning("Mise à jour des données d'un utilisateur est impossible :$request->nom -- $request->prenoms -- $request->phone -- $request->email -- $request->nom_atelier ".now());
                return response()->json([
                    'message' =>  'Mise à jour d\'un utilisateur a échoué',
                    'status' => 403
                ], 403);
            }
        }catch(Exception $exception){
            Log::critical("Mise à jour des données d'un utilisateur est impossible, Exceptoin $exception ".now());
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
     *     path="/api/users/{id}",
     *     operationId="delete_user",
     *     tags={"utilisateurs"},
     *     summary="Supprimer un utilisateur",
     *     description="Supprimer un utilisateur",
     *     @OA\Response(
     *          response="200",
     *          description="Supprime un seul utilisateur"
     *         ),
     *     @OA\Response(
     *          response="404",
     *          description="Utilisateur intouvable ou inexistant"
     *         ),
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur intouvable ou inexistant',
                'status' => 404
            ], 404);
        }
        if ($user->delete()){
            return response()->json([
                'message' => 'Utilisateur supprimé avec succès',
                'status' => 200
            ], 200);
        }
    }
}
