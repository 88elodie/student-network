<?php

declare(strict_types=1);
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
 
final class FileUploadController extends Controller
{
    public function process(Request $request): string
    {
        // We don't know the name of the file input, so we need to grab
        // all the files from the request and grab the first file.
        /** @var UploadedFile[] $files */
        $files = $request->allFiles();
 
        if (empty($files)) {
            abort(422, 'No files were uploaded.');
        }
 
        if (count($files) > 1) {
            abort(422, 'Only 1 file can be uploaded at a time.');
        }
 
        // Now that we know there's only one key, we can grab it to get
        // the file from the request.
        $requestKey = array_key_first($files);
 
        // If we are allowing multiple files to be uploaded, the field in the
        // request will be an array with a single file rather than just a
        // single file (e.g. - `csv[]` rather than `csv`). So we need to
        // grab the first file from the array. Otherwise, we can assume
        // the uploaded file is for a single file input and we can
        // grab it directly from the request.
        $file = is_array($request->input($requestKey))
            ? $request->file($requestKey)[0]
            : $request->file($requestKey);
 
        // Store the file in a temporary location and return the location
        // for FilePond to use.
        return $file->store(
            path: 'tmp/'.now()->timestamp.'-'.Str::random(20)
        );
    }

    public function index(){
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        $documents = Document::select()->orderBy('created_at', 'DESC')->paginate(10);

        return view('document.index', ['documents' => $documents, 'user_id' => session('id')]);
    }

    public function userindex($user){
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        $documents = Document::select()
        ->where('user_id', '=', $user)
        ->orderBy('created_at', 'DESC')
        ->paginate(10);

        $user = User::select()
        ->where('id', '=', $user)
        ->first();

        return view('document.userindex', ['documents' => $documents, 'user' => $user]);
    }

    public function show(Document $document){
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }

        $back = url()->previous();
        Session::put('back2', $back);

        return view('document.show', ['document' => $document]);
    }

    public function create(){
        if(session('name') === 'Admin'){
            return redirect(route('dashboard'))->withErrors('Admin cannot create posts.');
        }elseif(Auth::check()){
            return view('document.create', ['user_id' => session('id')]);
        }

        return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
    }

    public function destroy(Request $request){
        if(!Auth::check()){
            return redirect(route('auth.index'))->withErrors('You need to be signed in to access that page.');
        }elseif(session('id') != $request->user_id){
            return redirect(route('dashboard'))->withErrors('You do no have the permissions to do that action.');
        }

        $file = Document::select()
        ->where('id', '=', $request->file_id)
        ->delete();

        Storage::delete($request->file_location);
        return redirect()->route('document.index');
    }
}