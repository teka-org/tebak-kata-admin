<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        var_dump($data);
        if (User::where('name', $data['name'])->count() > 0) {
            throw new HttpResponseException(response([
                "errors" => [
                    "name" => [
                        "name already registered"
                    ]
                ]
            ], 400));
        }

        $user = new User();

        // Check for null pointer reference error in case the file is not present
        if ($request->hasFile('user')) {
            $userFile = $request->file('user');

            try {
                // Upload file user ke Cloudinary
                $cloudinaryUpload = Cloudinary::upload($userFile->getRealPath(), [
                    'folder' => 'teka_apps',
                    'public_id' => 'image_' . time(),
                    'overwrite' => true,
                ]);

                // Menyimpan URL user yang diunggah dalam array $data
                $data['user'] = $cloudinaryUpload->getSecurePath();
            } catch (\Throwable $e) {
                report($e);

                throw new HttpResponseException(
                    response(['message' => 'There was an error uploading the file'], 500)
                );
            }
        }

        if ($request->hasFile('purchasedusers')) {
            $data['purchasedusers'] = [];

            foreach ($request->file('purchasedusers') as $file) {
                try {
                    $cloudinaryUpload = Cloudinary::upload($file->getRealPath(), [
                        'folder' => 'teka_apps',
                        'public_id' => 'image_' . time(),
                        'overwrite' => true,
                    ]);

                    // Menyimpan URL user yang diunggah dalam array $purchasedusersUrls
                    $data['purchasedusers'][]['user'] = $cloudinaryUpload->getSecurePath();
                } catch (\Throwable $e) {
                    report($e);

                    throw new HttpResponseException(
                        response(['message' => 'There was an error uploading the file'], 500)
                    );
                }
            }
        }



        $user->fill($data);

        try {
            $user->save();
        } catch (\Throwable $e) {
            report($e);

            throw new HttpResponseException(
                response(['message' => 'Error saving data: ' . $e->getMessage()], 500)
            );
        }


        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "email not found!"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function get(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new HttpResponseException(response(['message' => 'User not found'], 404));
        }

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if ($request->hasFile('user')) {
            $userFile = $request->file('user');

            try {
                // Upload file user to Cloudinary
                $cloudinaryUpload = Cloudinary::upload($userFile->getRealPath(), [
                    'folder' => 'teka_apps',
                    'public_id' => 'image_' . time(),
                    'overwrite' => true,
                ]);

                $user->user = $cloudinaryUpload->getSecurePath();
            } catch (\Throwable $e) {
                report($e);

                throw new HttpResponseException(
                    response(['message' => 'There was an error uploading the user file'], 500)
                );
            }
        }

        try {
            $user->save();
        } catch (\Throwable $e) {
            report($e);

            throw new HttpResponseException(
                response(['message' => 'Error saving data: ' . $e->getMessage()], 500)
            );
        }

        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new HttpResponseException(response(['message' => 'User not found'], 404));
        }

        $user->token = null;

        try {
            $user->save();
        } catch (\Throwable $e) {
            report($e);

            throw new HttpResponseException(
                response(['message' => 'Error saving data: ' . $e->getMessage()], 500)
            );
        }

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }



//////////////////////////////////// VIEW /////////////////////////////////////////////////////

    public function index()
    {
        $users = User::all();
        $pageTitle = 'Teka | User';
        $user = Auth::guard('admin')->user();
        
        return view('pages.user.view-user', compact('users', 'pageTitle'), ['user' => $user]);
    }
 
  
     public function viewCreateUser()
    {
        // $users = user::all();
        $pageTitle = 'Teka | Create User';
        $user = Auth::guard('admin')->user();
        return view('pages.user.create-user', compact('pageTitle'), ['user' => $user]);
    }

     public function adminCreateUser(userRequest $request)
    {
        cloudinary()->upload(
            $request->file('image')->getRealPath(), [
            'transformation' => [
                'gravity' => 'auto',
                'width' => 300,
                'height' => 300,
                'crop' => 'crop'
            ]
        ])->getSecurePath();

        $uploadedFile = $request->file('image'); 
        $result = Cloudinary::upload($uploadedFile->getRealPath(), [
        'folder' => 'teka_apps', 
        'public_id' => 'image-' . time(),
        'overwrite' =>  true,
        ]);

        $image = $result->getSecurePath();

        $user = user::create([
            'image'         => $image,
            'user_name'   => $request->user_name,
            'price'         => $request->price,
            // 'status'        => $request->status,
        ]);

        return redirect()->away('/user')->with('success', 'user Created!.');
    }

    public function viewEditUser($id)
    {

        $user = User::find($id);
        $pageTitle = 'Teka | Edit user';
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        return view('pages.user.edit-user', compact('user', 'pageTitle'), ['user' => $user]);
    }

    public function adminUpdateUser(userRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $uploadedFile = $request->file('image');
        $result = Cloudinary::upload($uploadedFile->getRealPath(), [
            'folder' => 'teka_apps',
            'public_id' => 'image-' . time(),
            'overwrite' => true,
        ]);

        $image = $result->getSecurePath();

        $user->update([
            'image' => $image,
            'user_name' => $request->user_name,
            'price' => $request->price,
            // 'status' => $request->status,
        ]);
        return redirect()->away('/user')->with('success', 'user updated successfully!.');
    }

    public function adminDeleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->away('/user')->with('success', 'user Deleted!.');
        } else {
            return response()->json(['error' => 'user not found'], Response::HTTP_NOT_FOUND);
        }
    }
}