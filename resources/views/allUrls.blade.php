
<x-app-layout>
    <x-slot name="header">
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

        <div class="d-flex justify-content-between align-items-center mt-3 custom-pagination">
            {{ $urls->links() }}
        </div>
    </x-slot>
</x-app-layout>
