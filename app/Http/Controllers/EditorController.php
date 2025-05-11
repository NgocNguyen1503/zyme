<?php

namespace App\Http\Controllers;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditorController extends Controller
{
    /**
     * Display the editor index page with user's files.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get all request parameters
        $param = $request->all();

        // Get the ID of the currently authenticated user
        $userId = Auth::user()->id;

        // Query files belonging to the current user
        $files = File::where('user_id', $userId)
            // Add conditional filter by month if specified in request
            ->where(function ($query) use ($param) {
                if (isset($param['month']) && !is_null($param['month'])) {
                    $monthNow = Carbon::create($param['month']);
                    return $query->whereMonth('created_at', $monthNow);
                } else {
                    // Get current month
                    $monthNow = new Carbon();
                    return $query->whereMonth('created_at', $monthNow);
                }
            })
            ->get()
            // Map over results to add text representation of priority
            ->map(function ($item) {
                $item->txt_priority = File::CONVERT_PRIORITY_TXT[$item->priority];
                return $item;
            });
        // Return HTML view, passing the fetched and processed files
        return view('editor.index', compact('files'));
    }

    /**
     * Update the specified file record.
     *
     * @param Request $request
     * @param int $id The ID of the file to update.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Get all request parameters
        $param = $request->all();

        $file = File::find($id);
        if (isset($param['file']) && !is_null($param['file'])) {
            // File upload processing
            $fileName = $file->filename;

            // Find the last occurrence of the dot (.) in the filename to separate name and extension
            // Use strrpos instead of strpos to ensure we get the last dot in the filename
            $lastDot = strrpos($file->filename, '.');
            // Extract the filename without the extension
            $name = substr($file->filename, 0, $lastDot);
            // Extract the file extension
            $extension = substr($file->filename, $lastDot + 1);
            // Generate a new filename with a "_done" suffix and a timestamp to avoid duplicates
            $fileName = $name . "_done." . $extension;

            // Move uploaded file to calculated destination path.
            $directory = explode('@', Auth::user()->email)[0];
            $path = public_path('uploads\\' . $directory . '\\' . $fileName);
            $fileUpload = $request->file('file');
            move_uploaded_file($fileUpload, $path);

            return redirect()->back();
        }
        // Set status based on 'status' parameter,
        // CONFIRMED if present, ASSIGNED otherwise
        $file->status = isset($param['status']) ? File::STATUS_CONFIRM : File::STATUS_ASSIGN;
        $file->save();

        return redirect()->back();
    }

    /**
     * Handle the download of a specific file by its ID.
     *
     * @param Request $request
     * @param int $id The ID of the file to download.
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|string|\Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        // Get all request parameters (not used in this method)
        $param = $request->all();

        // Determine the user-specific directory based on the authenticated user's email prefix
        $directory = explode('@', Auth::user()->email)[0];
        // Find the file record in the database by its ID.
        $file = File::find($id);
        // Check if the file record was found. 
        if (!$file) {
            return "404 Not Found";
        }
        // Construct the expected file path on the server using the user's directory and filename from the database record.
        $path = public_path('uploads/' . $directory . '/' . $file->filename);
        // Check if the file exists in directory. 
        if (!file_exists($path)) {
            return "404 Not Found";
        }

        // If the file record was found, attempt to return the file as a download response.
        return response()->download($path);
    }
}
