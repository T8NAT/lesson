<div class="text-end" data-word-id="{{ $row->id }}" data-word-name="{{ $row->word }}">
    <a href="#" class="btn btn-sm btn-light btn-active-light-primary btn-flex btn-center" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">{{'الاجراءات'}}
        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
    <!--begin::Menu-->
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
        <!--begin::Menu item-->
{{--        @if (userHasPermission('word', 'can-edit'))--}}
            <div class="menu-item px-3">
                <a href="{{route('words.edit',$row->id)}}" class="menu-link px-3">{{'تعديل'}}</a>
            </div>
{{--        @endif--}}
        <!--end::Menu item-->
        <!--begin::Menu item-->
{{--        @if (userHasPermission('word', 'can-delete'))--}}
            <div class="menu-item px-3">
                <a href="#" class="menu-link px-3" data-kt-word-filter="delete_row">{{ 'حذف' }}</a>
            </div>
{{--        @endif--}}
        <!--end::Menu item-->
    </div>
    <!--end::Menu-->
</div>



