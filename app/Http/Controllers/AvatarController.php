<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AvatarRequest;
use App\Models\Avatar;
use App\Http\Resources\AvatarResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AvatarController extends Controller
{
    public function CreateAvatar(AvatarRequest $request): JsonResponse
    {
        $this-> validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png,|max:2048',
            'avatar_name' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        cloudinary()->upload(
            $request->file('image')->getRealPath(), [
            'transformation' => [
                'gravity' => 'auto',
                'width' => 300,
                'height' => 300,
                'crop' => 'crop'
            ]
        ])->getSecurePath();

        $uploadedFile = $request->file('image'); // assuming 'image' is the name of your file input
        $result = Cloudinary::upload($uploadedFile->getRealPath(), [
        'folder' => 'teka_apps', // Optional folder name in Cloudinary
        'public_id' => 'image-' . time(),
        'overwrite' =>  true,
        ]);

        // Once uploaded, you can get the public URL of the uploaded image
        $image = $result->getSecurePath();

        // $image = $request->file('image');
        // $image->storeAs('public/storage', $image->hashName());

        $avatar = Avatar::create([
            'image'         => $image,
            'avatar_name'   => $request->avatar_name,
            'price'         => $request->price,
            'status'        => $request->status,
        ]);

        return (new AvatarResource($avatar))->response()->setStatusCode(201);
        // return (new AvatarResource($avatar))->response()->json(['success' => 'Data Berhasil Disimpan!'], 201);
        // return (new AvatarResource($avatar))->response()->setStatusCode(201)->json(['success' => 'Data Berhasil Disimpan!']);
        // return response()->json(['success' => 'Data Berhasil Disimpan!'], 201);
        // return redirect()->route('/avatar')->with(['success' => 'Data Berhasil Disimpan!']);
    }
}






