<?php

namespace App\Http\Controllers;

use App\Events\UpdateProfile;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        $userId = auth()->user()->id;
        $user = User::findOrFail($userId);
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'avatar' => 'nullable'
        ]);

        $userId = auth()->user()->id;

        if ($request->file('avatar')) {
            # code...
            $time = time();
            $a = $request->file('avatar')->extension();
            $filename = $time.$request->name.'.'.$a;
            $request->file('avatar')->storeAs('public/avatars', $filename);
            $data = [
                'name' => $request->name,
                'avatar' => $filename
            ];
            $file = $data['avatar'];
        } else {
            $data = [
                'name' => $request->name
            ];
            $file = null;
        }

        broadcast(new UpdateProfile($userId, $data['name'], $file));

        User::where('id', $userId)->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data successfully updated'
        ]);
    }
}
