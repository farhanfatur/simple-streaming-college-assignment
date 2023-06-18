<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{

    protected $filePath = "videos/";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $assets = Asset::latest()->get();

        return view("pages.index", compact("assets"));
    }

    public function getAllToIndex() {
        $assets = Asset::latest()->get();

        return view("home", compact("assets"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'filename' => 'required|string|max:255',
            'formfile' => 'required|file|mimetypes:video/mp4',
        ]);

        $asset = new Asset;
        $asset->filename = $request->filename;
        if ($request->hasFile('formfile'))
        {
            $asset->original_name = $request->formfile->getClientOriginalName();
            $filePath = $this->filePath . $asset->filename.".mp4";
            $asset->url = $filePath;
            Storage::disk('public')->put($filePath, file_get_contents($request->formfile));
        }
        $asset->save();

        $assets = Asset::latest()->get();
        return view("pages.index", compact("assets"))->with("success", "Video has been succesfully upload");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assets = Asset::find($id)->latest()->get();

        return view("pages.create", compact("assets"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assets = Asset::find($id)->latest()->get();

        return view("pages.create", compact("assets"));
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
        $this->validate($request, [
            'filename' => 'required|string|max:255',
        ]);

        $assets = Asset::find($id);
        $filePath = $this->filePath;
        if ($assets) {
            $assets->url = $filePath;
            if ($request->hasFile('formfile')) {
                // TODO: remove old file
                Storage::delete($filePath.$assets->filename.".mp4");
                // TODO: insert new file
                $newFilePath = $filePath.$request->filename.".mp4";
                $assets->url = $newFilePath;
                Storage::disk('public')->put($newFilePath, file_get_contents($request->formfile));
            }else {
                Storage::move('public/videos/'.$assets->filename.".mp4", 'public/videos/'.$request->filename.".mp4");
                $assets->filename = $request->filename;
                $assets->url = $filePath.$request->filename.".mp4";
            }
            $assets->save();
            return redirect("/assets");
        }
        return redirect()->back()->with('error', 'error internal server');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $assets = Asset::find($id);
        $filePath = $this->filePath;
        if ($assets) {
            Storage::delete($filePath.$assets->filename.".mp4");

            $assets->delete();
            return redirect("/assets");
        }
        return redirect()->back()->with('error', 'file doesnt exist by id'.$id);
    }
}
