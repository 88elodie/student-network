<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;

class ImportProductController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request): RedirectResponse
    {
        if(session('name') === 'Admin'){
            return redirect(route('dashboard'))->withErrors('Admin cannot upload documents.');
        }

        $validated = $request->validate([
            'csv' => 'required|string',
        ]);
 
        // Copy the file from a temporary location to a permanent location.
        $fileLocation = Storage::putFile(
            path: 'public/'.$request->user_id,
            file: new File(Storage::path($validated['csv']))
        );

        $filetype = explode(".", $fileLocation);
        $fileLocation = explode("/", $fileLocation);
        $fileLocation = "storage/".$fileLocation[1]."/".$fileLocation[2];

        $document = Document::create([
            'name' => $request->name,
            'file_location' => $fileLocation,
            'file_type' => $filetype[1],
            'user_id' => $request->user_id
        ]);
 
        return redirect()
            ->route('document.index')
            ->with('success', 'Products imported successfully'); 
    }
}
