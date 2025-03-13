<div class="d-flex align-items-center">
<!--begin:: Avatar -->
<div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
    <a href="#">
        @if($admin->avatar)
            <div class="symbol-label">
                <img src="{{Storage::url($admin->avatar)}}" alt="avatar" class="w-100" />
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
    <a href="{{ route('admins.show',$admin->id) }}" class="text-gray-800 text-hover-primary mb-1">{{$admin->first_name .' '. $admin->last_name}}</a>
    <span>{{$admin->email}}</span>
</div>
<!--begin::User details-->
</div>
