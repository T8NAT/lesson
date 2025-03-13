<div class="text-end" data-author-id="{{ $author->id }}" data-author-name="{{ $author->name }}">
<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">الاجراءات
    <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
    @if (userHasPermission('teacher', 'can-edit'))
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="{{ route('authors.edit',$author->id) }}" class="menu-link px-3">تعديل</a>
        </div>
    @endif
    <!--end::Menu item-->
    @if (userHasPermission('teacher', 'can-delete'))
        <!--begin::Menu item-->
        <div class="menu-item px-3">
            <a href="#" class="menu-link px-3" data-kt-author-filter="delete_row">{{ 'حذف' }}</a>
        </div>
    @endif
    <!--end::Menu item-->
</div>
<!--end::Menu-->
</div>


