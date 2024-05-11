<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        
        if ($request->has('firstname')) {
            $query->where('firstname', $request->input('firstname'));
        }

        if ($request->has('lastname')) {
            $query->where('lastname', $request->input('lastname'));
        }

        if ($request->has('age')) {
            $query->where('age', $request->input('age'));
        }

        if ($request->has('nickname')) {
            $query->where('nickname', $request->input('nickname'));
        }

        
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('firstname', 'LIKE', "%$searchTerm%")
                      ->orWhere('lastname', 'LIKE', "%$searchTerm%")
                      ->orWhere('age', 'LIKE', "%$searchTerm%")
                      ->orWhere('nickname', 'LIKE', "%$searchTerm%");
            });
        }

        
        if ($request->has('sort')) {
            $sortField = $request->input('sort');
            $query->orderBy($sortField);
        } else {
            $query->orderBy('id', 'desc'); 
        }

        
        $limit = $request->input('limit', 10); 
        $users = $query->limit($limit)->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'age' => 'required|integer',
            'nickname' => 'required|string',
        ]);

       
        $user = User::create($validatedData);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        
        $validatedData = $request->validate([
            'firstname' => 'string',
            'lastname' => 'string',
            'age' => 'integer',
            'nickname' => 'string',
        ]);

       
        $user = User::findOrFail($id);

        
        $user->update($validatedData);

        return response()->json($user);
    }
}
