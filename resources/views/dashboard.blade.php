<x-app-layout>
    <style type="text/css">
/* Pagination buttons wrapper */
.relative.z-0.inline-flex {
    display: none !important;
}



    </style>
    <x-slot name="header">



         @if(Session::has('success'))
         <div class="alert alert-success alert-dismissible fade show" role="alert">
               {{Session::get('success')}}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(Auth::user()->role != 3)
       
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold text-gray-800">@if(Auth::user()->role == 1) {{'Clients'}} @else {{'Team Member'}} @endif</h2>
            <a href="{{route('shorturls.create')}}" class="btn btn-primary">Invite</a>
        </div>
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Client Name</th>
                      @if(Auth::user()->role == 1)
                        <th>Total User</th>
                      @else
                        <th>Role</th>
                      @endif
                    <th>Total Generated Url</th>
                    <th>Total Url Hits</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $item)
                <tr>
                     <td>{{$item['client_name']}} <br><small>{{$item['email']}}</small></td>
                    @if(Auth::user()->role == 1)
                     <td>{{$item['total_user'] ?? 0}}</td>
                    @else
                     <td>
                      @if($item['role'] == 2)
                      {{'Admin'}}
                      @else
                      {{'Member'}}
                      @endif
                     </td>
                    @endif
                     <td>{{$item['total_url']}}</td>
                     <td>{{$item['total_hits']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-between mt-3">
            <div>
                {{ $users->links() }}
            </div>

            <div>
                <a href="{{route('view.all.member')}}" class="btn btn-success" href="#">View All</a>
            </div>
        </div>

        @endif

        <br />

       <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Generated Short Url</h2>
    <div class="flex space-x-2 items-center">
        <form action="{{ route('download.urls') }}" method="POST" class="flex space-x-2 items-center">
    @csrf
    <div class="form-group">
        <select name="filter"
            class="border border-gray-300 rounded px-2 py-1 
                   appearance-none bg-none focus:outline-none">
            <option value="last_month">Last Month</option>
            <option value="last_week">Last Week</option>
            <option value="today">Today</option>
        </select>
    </div>    
    <button type="submit" class="btn btn-info">
        Download
    </button>                
</form>

       @if(Auth::user()->role != 1)
        <a href="{{ route('generate.url') }}" class="btn btn-primary">
            Generate
        </a>
       @endif
    </div>
</div>



         <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th>Short Url</th>
                    <th>Long Url</th>
                    <th>Hits</th>
                    <th>User</th>
                    <th>Created On</th>
                </tr>
            </thead>
            <tbody>
            @foreach($urls as $item)
                <tr>
                     <td><a href="{{url('/s/'.$item->short_code)}}">{{url($item->short_code)}}</a></td>
                     <td>{{$item->original_url}}</td>
                     <td>{{$item->hit_count}}</td>
                     <td>{{$item->user->name}}</td>
                     <td>{{ $item->created_at->format('d M y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

         <div class="d-flex justify-between mt-3">
            <div>
                {{ $urls->links() }}
            </div>

            <div>
                <a href="{{route('view.all.urls')}}" class="btn btn-success" href="#">View All</a>
            </div>
        </div>


    </x-slot>
</x-app-layout>
