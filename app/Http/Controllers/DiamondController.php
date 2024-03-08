<?php

namespace App\Http\Controllers;

use App\Models\Diamond;
use Illuminate\Http\Request;
use Illuminate\Http\Response; 
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DiamondRequest;
use App\Http\Resources\DiamondResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class DiamondController extends Controller
{
    public function createDiamond(DiamondRequest $request): JsonResponse
    {
        $this-> validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png,|max:2048',
            'quantity'=> 'required|numeric',
            'price'=> 'required|numeric',
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

        $diamond = Diamond::create([
            'image'         => $image,
            'quantity'      => $request->quantity,
            'price'         => $request->price,
        ]);

        return (new DiamondResource($diamond))->response()->setStatusCode(201);
    }

    public function getDiamond()
    {
        $diamond = Diamond::All();
        return response()->json(['diamonds' => $diamond], 200);
    }

    public function updateDiamond(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,jpg,png,|max:2048',
            'quantity'=> 'required|numeric',
            'price'=> 'required|numeric',
        ]);

        $diamond = Diamond::find($id);

        if(!$diamond){
            return response()->json(['message' => 'Diamond not found'], response::HTTP_NOT_FOUND);
        }

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

        $diamond->update([
            'image' => $image,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        return (new DiamondResource($diamond))->response()->setStatusCode(201);
    }

    public function deleteDiamond(Request $request, $id)
    {
        $diamond = Diamond::find ($id);

        if($diamond){
            $diamond->delete();
            return response()->json(['message' => 'Diamond deleted'], Response::HTTP_NOT_FOUND);
        }
    }
}
