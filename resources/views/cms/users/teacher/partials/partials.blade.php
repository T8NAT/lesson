<div class="d-flex align-items-center">
<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="#">
        @if($author->avatar)
            <div class="symbol-label">
                <img src="{{Storage::url($author->avatar)}}" alt="avatar" class="w-100" />
            </div>
        @else
            <div class="symbol-label">
                <img src="{{asset('assets/media/avatars/blank.png')}}" alt="avatar" class="w-100" />
            </div>
        @endif
    </a>
</div>
<!--end::Avatar-->
<!--begin::User details-->
<div class="d-flex flex-column">
    <a href="{{ route('authors.show',$author->id) }}" class="text-gray-800 text-hover-primary mb-1">{{$author->name}}</a>
    <span>{{$author->email}}</span>
</div>
<!--begin::User details-->
</div>
