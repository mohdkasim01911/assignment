<x-app-layout>
    <x-slot name="header">
       
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Invite New Client</h2>
        </div>

         <form method="post" action="{{route('shorturls.store')}}" class="row align-items-center">
            @csrf
              <div class="col-md-12 form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" value="{{old('name')}}" name="name" id="name">
                <div class="text-danger">
                    @error('name')
                      {{$message}}
                    @enderror
                </div>
              </div>

              
              
              <div class="col-md-12 form-group">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" value="{{old('email')}}" class="form-control" id="email" >
                 <div class="text-danger">
                    @error('email')
                      {{$message}}
                    @enderror
                </div>
              </div>

            @if(Auth::user()->role == 2)
              <div class="col-md-12 form-group">
                <label for="email" class="form-label">Role</label>
                <select class="form-control" name="role">
                    <option value="3">Member</option>
                    <option value="2">Admin</option>
                </select>
              </div>
               @endif
              <div class="col-12">
                <button class="btn btn-primary" type="submit">Send Invitation</button>
              </div>
        </form>
    </x-slot>
</x-app-layout>
