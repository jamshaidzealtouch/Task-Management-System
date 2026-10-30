<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display all users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return UserResource::collection($users);
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => [
                'required',
                Rule::in(['admin', 'manager', 'employee']),
            ],
            'organization_id' => [
                'nullable',
                'integer',
                'exists:organizations,id',
            ],
        ]);

        $validated['password'] = Hash::make(
            $validated['password']
        );

        $user = User::create($validated);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display a specific user.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    /**
     * Update a user.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['sometimes', 'required', 'string', 'min:8'],
            'role' => [
                'sometimes',
                'required',
                Rule::in(['admin', 'manager', 'employee']),
            ],
            'organization_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:organizations,id',
            ],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make(
                $validated['password']
            );
        }

        $user->update($validated);

        return new UserResource($user->fresh());
    }

    /**
     * Delete a user.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
