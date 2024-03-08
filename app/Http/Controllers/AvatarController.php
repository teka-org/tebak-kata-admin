<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response; 
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

        $uploadedFile = $request->file('image'); 
        $result = Cloudinary::upload($uploadedFile->getRealPath(), [
        'folder' => 'teka_apps', 
        'public_id' => 'image-' . time(),
        'overwrite' =>  true,
        ]);

        $image = $result->getSecurePath();

        $avatar = Avatar::create([
            'image'         => $image,
            'avatar_name'   => $request->avatar_name,
            'price'         => $request->price,
            'status'        => $request->status,
        ]);

        return (new AvatarResource($avatar))->response()->setStatusCode(201);
    }

        public function getAllAvatar()
    {
        $avatars = Avatar::all();
        return response()->json(['avatars' => $avatars], 200);
    }

    // public function updateAvatar(Avatar $avatar, Request $request)
    // {
    //         $this->validate($request, [
    //         'image' => 'required|image|mimes:jpeg,jpg,png,|max:2048',
    //         'avatar_name' => 'required|string',
    //         'price' => 'required|numeric',
    //         'status' => 'required|string',
    //         ]);

    //         cloudinary()->upload(
    //             $request->file('image')->getRealPath(), [
    //             'transformation' => [
    //                 'gravity' => 'auto',
    //                 'width' => 300,
    //                 'height' => 300,
    //                 'crop' => 'crop'
    //             ]
    //         ])->getSecurePath();

    //         $avatar = Avatar::find($id);
    
    //         $uploadedFile = $request->file('image'); 
    //         $result = Cloudinary::upload($uploadedFile->getRealPath(), [
    //         'folder' => 'teka_apps', 
    //         'public_id' => 'image-' . time(),
    //         'overwrite' =>  true,
    //         ]);
    
    //         $image = $result->getSecurePath();

    //         $avatar->update([
    //             'image'         => $image,
    //             'avatar_name'   => $request->avatar_name,
    //             'price'         => $request->price,
    //             'status'        => $request->status,
    //         ]);

    //         return (new AvatarResource($avatar))->response()->setStatusCode(201);
    // }


    public function updateAvatar(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'avatar_name' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $avatar = Avatar::find($id);

        if (!$avatar) {
            return response()->json(['message' => 'Avatar not found'], Response::HTTP_NOT_FOUND);
        }

        $uploadedFile = $request->file('image');
        $result = Cloudinary::upload($uploadedFile->getRealPath(), [
            'folder' => 'teka_apps',
            'public_id' => 'image-' . time(),
            'overwrite' => true,
        ]);

        $image = $result->getSecurePath();

        $avatar->update([
            'image' => $image,
            'avatar_name' => $request->avatar_name,
            'price' => $request->price,
            'status' => $request->status,
        ]);


        // return response()->json(['avatar' => $avatar], Response::HTTP_OK);
        return (new AvatarResource($avatar))->response()->setStatusCode(201);
    }

    

        public function deleteAvatar(Request $request, $id)
    {
        $avatar = Avatar::find($id);

        if ($avatar) {
            $avatar->delete();
            return response()->json(['message' => 'Avatar Deleted'], Response::HTTP_OK);
        } else {
            return response()->json(['error' => 'Avatar not found'], Response::HTTP_NOT_FOUND);
        }
    }


}






