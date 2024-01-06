@if ($errors->any())
    <div class="alert red" style="background-color: #ff4d4d; color: white;">
        <button type="button" class="close" data-dismiss="alert"><span>×</span><span class="sr-only">Close</span>
        </button>
        @foreach ($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    </div>
@endif
