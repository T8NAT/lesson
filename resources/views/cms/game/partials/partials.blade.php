<div class="d-flex align-items-center">
    <!--begin:: Avatar -->
    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
        <a href="#">
            @if($row->icon)
                <div class="symbol-label">
                    <img src="{{Storage::url($row->icon)}}" alt="icon" class="w-100" />
                </div>
            @else
                <div class="symbol-label">
                    <img src="{{asset('assets/media/avatars/blank.png')}}" alt="avatar" class="w-100" />
                </div>
            @endif
        </a>
    </div>
    <!--end::Avatar-->

</div>
