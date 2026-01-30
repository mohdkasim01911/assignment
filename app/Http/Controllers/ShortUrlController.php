<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User,ShortUrl};
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Invite;
use Auth;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{

    public function dashboard(){

        $user = auth()->user();

        // $users = User::where('role','!=',1)->latest()->paginate(10);
        if ($user->role == 1) {

        $users = $users = $this->getUserData(2);
        $urls =  $this->getAllUrls(2);

        }
        // ADMIN
        elseif ($user->role == 2) {
         $users = $users = $this->getUserData(2);
           $urls =  $this->getAllUrls(2);
        }
        // MEMBER
        else {

            $users = collect(); // empty (member ko users nahi dikhane)
            $urls =  $this->getAllUrls(2);
        }

        return view('dashboard',compact('users','urls'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('invite');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);
        $password = Hash::make('Test@123');
        $data['password'] = $password;

        if($request->role){
          $data['role'] =$request->role;
          $data['parent_id'] = Auth::user()->id;
        }

        User::create($data);

        $user = [
           'email' => $request->email,
           'password' => 'Test@123',
        ];

        Mail::to($request->email)->send(new Invite($user));

        return redirect()->route('dashboard')->with('success','Client Created');
       
    }

    public function generate_url(){
        return view('genrateUrl');
    }

    public function generate_url_store(Request $request){
        $data = $request->validate([
          'url' => 'required|url'
        ]);
          
        $shortUrl = ShortUrl::create([
            'user_id' => Auth::user()->id, // optional
            'original_url' => $request->url,
            'short_code' => Str::random(6), // random 6-char code
        ]);

        return redirect()->route('dashboard')->with('success','Url Created');
       
    }

    public function redirect($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

        // increment hit count
        $shortUrl->increment('hit_count');

        return redirect($shortUrl->original_url);
    }

    public function downloadUrl(Request $request)
    {

   

        $filter = $request->filter; // month, week, day
        $query = ShortUrl::query();

        // Filter based on selection
        if ($filter === 'month') {
            $query->where('created_at', '>=', Carbon::now()->subMonth());
        } elseif ($filter === 'week') {
            $query->where('created_at', '>=', Carbon::now()->subWeek());
        } elseif ($filter === 'day') {
            $query->whereDate('created_at', Carbon::today());
        }

        if(Auth::user()->role == 1){

              $urls = $query->with('user')->get();

        }elseif(Auth::user()->role == 2){

             $urls = $query->with('user')->get();

        }else{

        }

        // eager load user

        // Create CSV using output buffer
        $fileName = 'short_urls_' . ($filter ?? 'all') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($urls) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, ['Name', 'Short URL', 'Original URL', 'Created At']);

            foreach ($urls as $url) {
                fputcsv($file, [
                    $url->user?->name ?? 'N/A',
                    url('/s/'.$url->short_code), // Full short URL
                    $url->original_url,
                    $url->created_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
}

public function allMember(){

    
    $users = $this->getUserData(5);

    return view('allMember',compact('users'));
}

public function allUrls(){

        $urls =  $this->getAllUrls(5);

        return view('allUrls',compact('urls'));
    
}


public function getUserData($per_page){


  $user = auth()->user();



        if ($user->role == 1) {

              $users = User::where('role',2)
                ->whereNull('parent_id')
                ->latest()
                ->paginate($per_page)
                ->through(function ($item) {

                    $parentId = empty($item->parent_id) ? $item->id : $item->parent_id;

                    if($item->role == 3){
                       $parentId = $item->id; 
                    }

                    $userIds = User::where('parent_id', $parentId)->pluck('id')->toArray();
                     $total_user = count($userIds);
                    $userIds[] = $item->id;

                return [
                    'client_name' => $item->name,
                    'email'       => $item->email,
                    'role'       => $item->role,
                    'total_user'  => $total_user,
                    'total_url'   => ShortUrl::whereIn('user_id', $userIds)->count(),
                    "total_hits" =>  ShortUrl::whereIn('user_id', $userIds)->sum('hit_count')
                ];
                });
    
           }

        // ADMIN
        elseif ($user->role == 2) {

            if(Auth::user()->parent_id != ''){
                $parentId = Auth::user()->parent_id;
            }else{
                $parentId = $user->id;
            }
    
            $users = User::where('parent_id',$parentId)
                ->latest()
                ->paginate($per_page)
                ->through(function ($item) {

                    $parentId = empty($item->parent_id) ? $item->id : $item->parent_id;

                    if($item->role == 3){
                       $parentId = $item->id; 
                    }

                    $userIds = User::where('parent_id', $parentId)->pluck('id')->toArray();
                    $userIds[] = $item->id;
                return [
                    'client_name' => $item->name,
                    'email'       => $item->email,
                    'role'       => $item->role,
                    'total_url'   => ShortUrl::whereIn('user_id', $userIds)->count(),
                    "total_hits" =>  ShortUrl::whereIn('user_id', $userIds)->sum('hit_count')
                ];
            });

        }

     return $users;
}

public function getAllUrls($per_page){

    $user = auth()->user();

        // $users = User::where('role','!=',1)->latest()->paginate(10);
        if ($user->role == 1) {
            $urls  = ShortUrl::latest()->paginate($per_page);
        }
        // ADMIN
        elseif ($user->role == 2) {

            if(Auth::user()->parent_id != ''){
                $parentId = Auth::user()->parent_id;
            }else{
                $parentId = $user->id;
            }

            $get_id = User::where('parent_id',$parentId)->pluck('id');
            $get_id[] = Auth::user()->id;
           
            // Admin ke generated URLs
            $urls = ShortUrl::whereIn('user_id', $get_id)
                       ->latest()
                       ->paginate($per_page);
        }
        // MEMBER
        else {

            $urls  = ShortUrl::where('user_id', $user->id)
                        ->latest()
                        ->paginate($per_page);
        }

        return $urls;


}



}
