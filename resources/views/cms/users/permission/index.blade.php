@extends('cms.layout.master')
@section('toolbar-title','كافة الصلاحيات')
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','قائمة الصلاحيات')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Card-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header mt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1 me-5">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-permissions-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="بحث الصلاحيات" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    @if (userHasPermission('permission', 'can-add'))
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_permission">
                            <i class="ki-duotone ki-plus-square fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>اضافة صلاحية جديدة</button>
                        <!--end::Button-->
                    </div>
                    @endif
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">الاسم</th>
                            <th class="min-w-250px">مخصص لـ</th>
                            <th class="min-w-125px">تاريخ الانشاء</th>
                            <th class="text-end min-w-100px">الاجراءات</th>
                        </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        @foreach($permissions as $permission)
                        <tr data-permission-id="{{ $permission->id }}">
                            <td>{{$permission->name}}</td>
                            <td>
                                <a href="{{ route('roles.show',$permission->role->id) }}" class="badge badge-light-primary fs-7 m-1">{{$permission->role->name}}</a>
                            </td>
                            <td>{{$permission->created_at}}</td>
                            <td class="text-end">
                                @if (userHasPermission('permission', 'can-edit'))
                                <a href="{{ route('permissions.edit',$permission->id) }}" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">
                                    <i class="ki-duotone ki-setting-3 fs-3">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                    </i>
                                </a>
                                @endif
                                    @if (userHasPermission('permission', 'can-delete'))
                                <form id="delete-permission-form-{{ $permission->id }}" method="POST" class="btn btn-icon btn-active-light-primary w-30px h-30px" action="{{ route('permissions.destroy', $permission->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-icon btn-active-light-primary w-30px h-30px"  data-delete-url="{{ route('permissions.destroy', $permission->id) }}" data-kt-permissions-table-filter="delete_row">
                                        <i class="ki-duotone ki-trash fs-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                        </i>
                                    </button>
                                </form>
                                    @endif
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
            <!--begin::Modals-->
            <!--begin::Modal - Add permissions-->
            <div class="modal fade" id="kt_modal_add_permission" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">اضافة صلاحية جديدة</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-permissions-modal-action="close">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_add_permission_form" class="form" data-kt-redirect="{{route('permissions.index')}}" action="{{route('permissions.store')}}" method="POST">
                                @csrf
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mb-2">
                                        <span class="required">الادوار</span>
                                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Role names is required to be unique.">
															<i class="ki-duotone ki-information fs-7">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
															</i>
														</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select form-select-solid" name="role_id" >
                                    <option disabled selected hidden="">حدد خياراً</option>
                                       @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                  </select>
                                    <!--end::Input-->
                                </div>
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mb-2">
                                        <span class="required">اسم الصلاحية</span>
                                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Permission names is required to be unique.">
															<i class="ki-duotone ki-information fs-7">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
															</i>
														</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="قم بادخال اسم الصلاحية" name="name" />
                                    <!--end::Input-->
                                </div>
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
                                                <td class="text-gray-800">
                                                    <span class="ms-1" data-bs-toggle="tooltip" title="السماح بكل الصلاحيات للنظام">
																			<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
																				<span class="path1"></span>
																				<span class="path2"></span>
																				<span class="path3"></span>
																			</i>
																		</span></td>
                                                <td>
                                                    <!--begin::Checkbox-->
                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                        <input class="form-check-input" type="checkbox" value="1" id="kt_roles_select_all" />
                                                        <span class="form-check-label" for="kt_roles_select_all">تحديد الكل</span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                </td>
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الاقسام</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[category][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[category][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[category][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[category][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الالعاب</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[game][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[game][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[game][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[game][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الانواع</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[type][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[type][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[type][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[type][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">المراحل</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[level][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[level][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[level][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[level][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الكلمات</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[word][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[word][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[word][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[word][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الصور</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[image][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[image][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[image][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[image][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الصوتيات</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[audio][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[audio][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[audio][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[audio][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">المدراء</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[admin][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[admin][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[admin][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[admin][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الطلاب</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[student][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[student][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[student][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[student][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">المعلمين</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[teacher][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[teacher][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[teacher][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[teacher][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الادوار</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[role][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[role][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[role][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[role][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">الصلاحيات</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[permission][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[permission][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[permission][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1" name="permissions[permission][can-delete]" />
                                                            <span class="form-check-label">حذف</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </td>
                                                <!--end::Input group-->
                                            </tr>
                                            <!--end::Table row-->
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-permissions-modal-action="cancel">الغاء</button>
                                    <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                                        <span class="indicator-label">اضافة</span>
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
            <!--end::Modal - Add permissions-->
            <!--end::Modals-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->

@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/users-management/permissions/list.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/users-management/permissions/add-permission.js')}}"></script>
    <script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script>
@endsection
