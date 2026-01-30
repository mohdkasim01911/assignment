
<x-app-layout>
    <x-slot name="header">
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
        <div class="d-flex justify-content-between align-items-center mt-3 custom-pagination">
            {{ $users->links() }}
        </div>




       



        


    </x-slot>
</x-app-layout>
