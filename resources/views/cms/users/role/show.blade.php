@extends('cms.layout.master')
@section('toolbar-title','عرض الدور'.': '.$role->name)
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','عرض الدور'.': '.$role->name)
@section('style')
        <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-lg-200px w-xl-300px mb-10">
                    <!--begin::Card-->
                    <div class="card card-flush">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2 class="mb-0">{{$role->name}}</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Permissions-->
                            <div class="d-flex flex-column text-gray-600">
                                @foreach($role->permissions as $permission)
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span>{{$permission->name}}</div>
                                @endforeach
                            </div>
                            <!--end::Permissions-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer pt-0">
                            <button type="button" class="btn btn-light btn-active-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">تعديل</button>
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                    <!--begin::Modal-->
                    <!--begin::Modal - Update role-->
                    <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
                        <!--begin::Modal dialog-->
                        <div class="modal-dialog modal-dialog-centered mw-750px">
                            <!--begin::Modal content-->
                            <div class="modal-content">
                                <!--begin::Modal header-->
                                <div class="modal-header">
                                    <!--begin::Modal title-->
                                    <h2 class="fw-bold">تعديل الدور</h2>
                                    <!--end::Modal title-->
                                    <!--begin::Close-->
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-roles-modal-action="close">
                                        <i class="ki-duotone ki-cross fs-1">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                    <!--end::Close-->
                                </div>
                                <!--end::Modal header-->
                                <!--begin::Modal body-->
                                <div class="modal-body scroll-y mx-5 my-7">
                                    <!--begin::Form-->
                                    <form id="kt_modal_update_role_form" class="form" action="{{route('roles.update',$role->id)}}">
                                        <!--begin::Scroll-->
                                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header" data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">
                                                    <span class="required">اسم الدور</span>
                                                </label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <input class="form-control form-control-solid" placeholder="قم بادخال اسم الدور هنا" name="name" value="{{$role->name}}" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Permissions-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">صلاحيات الدور</label>
                                                <!--end::Label-->
                                                <!--begin::Table wrapper-->
                                                <div class="table-responsive">
                                                    <!--begin::Table-->
                                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                                        <!--begin::Table body-->
                                                        <tbody class="text-gray-600 fw-semibold">
                                                        <!--begin::Table row-->
                                                        <tr>
                                                            <td class="text-gray-800">امكانية الوصول
                                                                <span class="ms-1" data-bs-toggle="tooltip" title="السماح بكافة الصلاحية للنظام">
																					<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
																						<span class="path1"></span>
																						<span class="path2"></span>
																						<span class="path3"></span>
																					</i>
																				</span></td>
                                                            <td>
                                                                <!--begin::Checkbox-->
{{--                                                                <label class="form-check form-check-sm form-check-custom form-check-solid me-9">--}}
{{--                                                                    <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />--}}
{{--                                                                    <span class="form-check-label" for="kt_roles_select_all">نحديد الكل</span>--}}
{{--                                                                </label>--}}
                                                                <!--end::Checkbox-->
                                                            </td>
                                                        </tr>
                                                        <!--end::Table row-->
                                                        <!--begin::Table row-->
                                                        @foreach($role->permissions as $permission)
                                                        <tr>
                                                            <!--begin::Label-->
                                                            <td class="text-gray-800">{{$permission->name}}</td>
                                                            <!--end::Label-->
                                                            <!--begin::Input group-->
                                                            <td>
                                                                <!--begin::Wrapper-->
                                                                <div class="d-flex">
                                                                    <!--begin::Checkbox-->
                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input" type="checkbox" @if(isset($permission['permissions']['category']['can-add'])) checked  @endif disabled value="1" name="permissions[can-add]" />
                                                                        <span class="form-check-label">اضافة</span>
                                                                    </label>
                                                                    <!--end::Checkbox-->
                                                                    <!--begin::Checkbox-->
                                                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input" type="checkbox" @if(isset($permission['permissions']['category']['can-edit'])) checked  @endif  disabled value="1" name="user_management_write" />
                                                                        <span class="form-check-label">تعديل</span>
                                                                    </label>
                                                                    <!--end::Checkbox-->
                                                                    <!--begin::Checkbox-->
                                                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input" type="checkbox" @if(isset($permission['permissions']['category']['can-show'])) checked  @endif  disabled value="" name="user_management_create" />
                                                                        <span class="form-check-label">عرض</span>
                                                                    </label>
                                                                    <!--end::Checkbox-->
                                                                    <!--begin::Checkbox-->
                                                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input" type="checkbox" @if(isset($permission['permissions']['category']['can-delete'])) checked  @endif  disabled value="1" name="user_management_write" />
                                                                        <span class="form-check-label">حذف</span>
                                                                    </label>
                                                                    <!--end::Checkbox-->
                                                                </div>
                                                                <!--end::Wrapper-->
                                                            </td>
                                                            <!--end::Input group-->
                                                        </tr>
                                                        @endforeach
                                                        <!--end::Table row-->

                                                        </tbody>
                                                        <!--end::Table body-->
                                                    </table>
                                                    <!--end::Table-->
                                                </div>
                                                <!--end::Table wrapper-->
                                            </div>
                                            <!--end::Permissions-->
                                        </div>
                                        <!--end::Scroll-->
                                        <!--begin::Actions-->
                                        <div class="text-center pt-15">
                                            <button type="reset" class="btn btn-light me-3" data-kt-roles-modal-action="cancel">الغاء</button>
                                            <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                                                <span class="indicator-label">تأكيد</span>
                                                <span class="indicator-progress">الرجاء الانتظار...
																<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                            </button>
                                        </div>
                                        <!--end::Actions-->
                                    </form>
                                    <!--end::Form-->
                                </div>
                                <!--end::Modal body-->
                            </div>
                            <!--end::Modal content-->
                        </div>
                        <!--end::Modal dialog-->
                    </div>
                    <!--end::Modal - Update role-->
                    <!--end::Modal-->
                </div>
                <!--end::Sidebar-->
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-10">
                    <!--begin::Card-->
                    <div class="card card-flush mb-6 mb-xl-9">
                        <!--begin::Card header-->
                        <div class="card-header pt-5">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2 class="d-flex align-items-center">المستخدمين المخصصين
                                    <span class="text-gray-600 fs-6 ms-1">({{$role->users->count()}})</span></h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <!--begin::Search-->
                                <div class="d-flex align-items-center position-relative my-1" data-kt-view-roles-table-toolbar="base">
                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <input type="text" data-kt-roles-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="بحث المستخدمين" />
                                </div>
                                <!--end::Search-->
                                <!--begin::Group actions-->
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-view-roles-table-toolbar="selected">
                                    <div class="fw-bold me-5">
                                        <span class="me-2" data-kt-view-roles-table-select="selected_count"></span>المحدد</div>
                                    <button type="button" class="btn btn-danger" data-kt-view-roles-table-select="delete_selected">حذف المحدد</button>
                                </div>
                                <!--end::Group actions-->
                            </div>
                            <!--end::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Table-->
                            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_roles_view_table">
                                <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_roles_view_table .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-10px">#</th>
                                    <th class="min-w-150px">المستخدم</th>
                                    <th class="min-w-125px">تاريخ الانضمام</th>
                                    <th class="text-end min-w-100px">الاجراءات</th>
                                </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                @foreach($role->users as $key=>$user)
                                <tr>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" value="1" />
                                        </div>
                                    </td>
                                    <td>{{$key+1}}</td>
                                    <td class="d-flex align-items-center">
                                        <!--begin:: Avatar -->
                                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                            <a href="{{ route('users.show',$user->id) }}">
                                                @if($user->avatar)
                                                    <div class="symbol-label">
                                                        <img src="{{Storage::url($user->avatar)}}" alt="avatar" class="w-100" />
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
                                            <a href="{{ route('users.show',$user->id) }}" class="text-gray-800 text-hover-primary mb-1">{{$user->first_name}}</a>
                                            <span>{{$user->email}}</span>
                                        </div>
                                        <!--begin::User details-->
                                    </td>
                                    <td>{{$user->created_at}}</td>
                                    <td class="text-end">
                                        <a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">الاجراءات
                                            <i class="ki-duotone ki-down fs-5 m-0"></i></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{route('users.show',$user->id)}}" class="menu-link px-3">عرض</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-kt-roles-table-filter="delete_row">حذف</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <!--end::Table-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/users-management/roles/view/view.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/users-management/roles/view/update-role.js')}}"></script>
    <script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script>
@endsection
