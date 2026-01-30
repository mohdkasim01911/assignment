<x-app-layout>
    <x-slot name="header">
       
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Generate Short Url</h2>
        </div>

         <form method="post" action="{{route('generate.url.store')}}" class="row align-items-center">
            @csrf
              <div class="col-md-12 form-group">
                <label for="url" class="form-label">Name</label>
                <input type="text" class="form-control" value="{{old('url')}}" name="url" id="url" placeholder="eg. https://www.youtube.com/">
                <div class="text-danger">
                    @error('url')
                      {{$message}}
                    @enderror
                </div>
              </div>
              <div class="col-12">
                <button class="btn btn-primary" type="submit">Send Invitation</button>
              </div>
        </form>
    </x-slot>
</x-app-layout>
