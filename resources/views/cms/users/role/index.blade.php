@extends('cms.layout.master')
@section('toolbar-title','كافة الادوار')
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','قائمة الادوار')
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Row-->
            <div class="row container-fluid row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
                @foreach($roles as $role)
                <!--begin::Col-->
                <div class="col-md-4">
                    <!--begin::Card-->
                    <div class="card card-flush h-md-100">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{$role->name}}</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-1">
                            <!--begin::Users-->
                            <div class="fw-bold text-gray-600 mb-5">إجمالي المستخدمين الذين لديهم هذا الدور : {{ $role->users->count() }} </div>
                            <!--end::Users-->
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
                        <div class="card-footer flex-wrap pt-0">
                            @if (userHasPermission('role', 'can-show'))
                            <a href="{{ route('roles.show',$role->id) }}" class="btn btn-light btn-active-primary my-1 me-2">عرض</a>
                            @endif
                                @if (userHasPermission('role', 'can-edit'))
                            <button type="button" class="btn btn-light btn-active-light-primary my-1" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_role{{$role->id}}">تعديل</button>
                                @endif
                                @if (userHasPermission('role', 'can-delete'))
                            <a href="#" onclick=" confirmDelete (this, '{{ $role->id }}')" class="btn btn-danger btn-active-light-danger my-1">حذف</a>
                                @endif
                        </div>
                        <!--end::Card footer-->
                    </div>
                    <!--end::Card-->
                </div>
                    <!--begin::Modal - Update role-->
                    <div class="modal fade" id="kt_modal_edit_role{{$role->id}}" data-role-id="{{ $role->id }}" tabindex="-1" aria-hidden="true">
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
                                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" data-kt-roles-modal-action="close">
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
                                    <form id="kt_modal_edit_role_form" method="POST" class="form" action="{{route('roles.update',$role->id)}}" data-kt-redirect="{{route('roles.index')}}">
                                       @csrf
                                        @method('PUT')
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
                                                <input class="form-control form-control-solid" placeholder="قم بادخال اسم الدور هنا"  name="name" value="{{$role->name}}" />
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Permissions-->
                                            <div class="fv-row">
                                                <!--begin::Label-->
                                                <label class="fs-5 fw-bold form-label mb-2">صلاحايات الدور</label>
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
                                                                <span class="ms-1" data-bs-toggle="tooltip" title="Allows a full access to the system">
																			<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
																				<span class="path1"></span>
																				<span class="path2"></span>
																				<span class="path3"></span>
																			</i>
																		</span></td>
                                                        </tr>
                                                        <!--end::Table row-->
                                                        <!--begin::Table row-->
                                                        @foreach($role->permissions as $permission)
                                                        <tr>
                                                            <!--begin::Label-->
                                                            <td class="text-gray-800">{{$permission->name}}</td>
                                                            <!--end::Label-->
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
                                            <button type="button" class="btn btn-light me-3" onclick="cancel_button({{$role->id}})"  id="cancelButton">الغاء</button>
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
                @endforeach
                <!--end::Col-->
                    @if (userHasPermission('role', 'can-add'))
                <!--begin::Add new card-->
                <div class="col-md-4">
                    <!--begin::Card-->
                    <div class="card h-md-100">
                        <!--begin::Card body-->
                        <div class="card-body d-flex flex-center">
                            <!--begin::Button-->
                            <button type="button" class="btn btn-clear d-flex flex-column flex-center" data-bs-toggle="modal" data-bs-target="#kt_modal_add_role">
                                <!--begin::Illustration-->
                                <img src="{{asset('assets/media/illustrations/dozzy-1/4.png')}}" alt="" class="mw-100 mh-150px mb-7" />
                                <!--end::Illustration-->
                                <!--begin::Label-->
                                <div class="fw-bold fs-3 text-gray-600 text-hover-primary">اضافة دور جديد</div>
                                <!--end::Label-->
                            </button>
                            <!--begin::Button-->
                        </div>
                        <!--begin::Card body-->
                    </div>
                    <!--begin::Card-->
                </div>
                <!--begin::Add new card-->
                    @endif
            </div>
            <!--end::Row-->
            <!--begin::Modals-->
            <!--begin::Modal - Add role-->
            <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-750px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">اضافة دور جديد</h2>
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
                        <div class="modal-body scroll-y mx-lg-5 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_add_role_form" class="form" action="{{route('roles.store')}}" data-kt-redirect="{{route('roles.index')}}" method="POST">
                                @csrf
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-10">
                                        <!--begin::Label-->
                                        <label class="fs-5 fw-bold form-label mb-2">
                                            <span class="required">اسم الدور</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input class="form-control form-control-solid" placeholder="قم بادخال اسم الدور هنا" name="name" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Permissions-->
{{--                                    <div class="fv-row">--}}
{{--                                        <!--begin::Label-->--}}
{{--                                        <label class="fs-5 fw-bold form-label mb-2">صلاحيات الدور</label>--}}
{{--                                        <!--end::Label-->--}}
{{--                                        <!--begin::Table wrapper-->--}}
{{--                                        <div class="table-responsive">--}}
{{--                                            <!--begin::Table-->--}}
{{--                                            <table class="table align-middle table-row-dashed fs-6 gy-5">--}}
{{--                                                <!--begin::Table body-->--}}
{{--                                                <tbody class="text-gray-600 fw-semibold">--}}
{{--                                                <!--begin::Table row-->--}}
{{--                                                @foreach($roles as $role)--}}
{{--                                                    @foreach($role->permissions as $permission)--}}
{{--                                                        <!--begin::Table row-->--}}
{{--                                                        <tr>--}}
{{--                                                            <td class="text-gray-800">Administrator Access--}}
{{--                                                                <span class="ms-1" data-bs-toggle="tooltip" title="Allows a full access to the system">--}}
{{--																			<i class="ki-duotone ki-information-5 text-gray-500 fs-6">--}}
{{--																				<span class="path1"></span>--}}
{{--																				<span class="path2"></span>--}}
{{--																				<span class="path3"></span>--}}
{{--																			</i>--}}
{{--																		</span></td>--}}
{{--                                                            <td>--}}
{{--                                                                <!--begin::Checkbox-->--}}
{{--                                                                <label class="form-check form-check-sm form-check-custom form-check-solid me-9">--}}
{{--                                                                    <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />--}}
{{--                                                                    <span class="form-check-label" for="kt_roles_select_all">Select all</span>--}}
{{--                                                                </label>--}}
{{--                                                                <!--end::Checkbox-->--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <!--end::Table row-->--}}
{{--                                                        <!--begin::Table row-->--}}
{{--                                                        <tr>--}}
{{--                                                            <!--begin::Label-->--}}
{{--                                                            <td class="text-gray-800">{{$permission->name}}</td>--}}
{{--                                                            <!--end::Label-->--}}
{{--                                                            <!--begin::Input group-->--}}
{{--                                                            <td>--}}
{{--                                                                <!--begin::Wrapper-->--}}
{{--                                                                <div class="d-flex">--}}
{{--                                                                    <!--begin::Checkbox-->--}}
{{--                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">--}}
{{--                                                                        <input class="form-check-input" type="checkbox" value="" name="user_management_read" />--}}
{{--                                                                        <span class="form-check-label">Read</span>--}}
{{--                                                                    </label>--}}
{{--                                                                    <!--end::Checkbox-->--}}
{{--                                                                    <!--begin::Checkbox-->--}}
{{--                                                                    <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">--}}
{{--                                                                        <input class="form-check-input" type="checkbox" value="" name="user_management_write" />--}}
{{--                                                                        <span class="form-check-label">Write</span>--}}
{{--                                                                    </label>--}}
{{--                                                                    <!--end::Checkbox-->--}}
{{--                                                                    <!--begin::Checkbox-->--}}
{{--                                                                    <label class="form-check form-check-custom form-check-solid">--}}
{{--                                                                        <input class="form-check-input" type="checkbox" value="" name="user_management_create" />--}}
{{--                                                                        <span class="form-check-label">Create</span>--}}
{{--                                                                    </label>--}}
{{--                                                                    <!--end::Checkbox-->--}}
{{--                                                                </div>--}}
{{--                                                                <!--end::Wrapper-->--}}
{{--                                                            </td>--}}
{{--                                                            <!--end::Input group-->--}}
{{--                                                        </tr>--}}
{{--                                                        <!--end::Table row-->--}}
{{--                                                @endforeach--}}
{{--                                                @endforeach--}}
{{--                                                <!--end::Table row-->--}}
{{--                                                </tbody>--}}
{{--                                                <!--end::Table body-->--}}
{{--                                            </table>--}}
{{--                                            <!--end::Table-->--}}
{{--                                        </div>--}}
{{--                                        <!--end::Table wrapper-->--}}
{{--                                    </div>--}}
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
            <!--end::Modal - Add role-->

            <!--end::Modals-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script src="{{asset('assets/js/custom/apps/users-management/roles/list/add.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/users-management/roles/list/update-role.js')}}"></script>
    <script src="{{asset('assets/js/axios.js')}}"></script>
    <script>

        function confirmDelete(app, id) {
            Swal.fire({
                title: 'هل انت متأكد من عملية الحذف',
                text: "لن تتمكن من الرجوع عن هذا",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'حسنا ، احذفه',
                cancelButtonText: 'إلغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        deleteRole(app, id)
                    )

                }
            })
        }

        function deleteRole(app, id) {
            axios.delete('/dashboard/roles/' + id)
                .then(function (response) {
                    // handle success
                    console.log(response);
                    app.closest('tr').remove();
                    showMessage(response.data)
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                    showMessage(error.response.data);
                })
                .then(function () {
                    // always executed
                });
        }

        function showMessage(data) {
            let timerInterval
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })
        }
    </script>
@endsection
