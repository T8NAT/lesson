@extends('cms.layout.master')
@section('toolbar-title','تعديل الصلاحية')
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','تعديل الصلاحية')
@section('sub-breadcrumb','تعديل الصلاحية')
@section('content')
    <!--begin::Card-->
    <div class="row g-5 g-xl-8">
        <div class="col-xl-12">
            <!--begin::List Widget 6-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0">
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-0">
                    <!----------------------------------------------------------------------->
                    <!-------------------------------Form---------------------------------------->
                    <!--begin:Form-->
                    <!--------------------- this is out takbeeees ---------------------------->
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">تعديل الصلاحية</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div
                            class="text-gray-400 fw-bold fs-5">يمكنك تصفح قائمة الصلاحيات من
                            <a href="{{route('permissions.index')}}"
                               class="fw-bolder link-primary">هنا</a>.
                        </div>
                        <!--end::Description-->
                    </div>
                    <!--end::Heading-->
                    <form id="kt_ecommerce_add_category_form" class="form" data-kt-redirect="{{route('permissions.index')}}" enctype="multipart/form-data" action="{{route('permissions.update',$permission->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Notice-->
                            <!--begin::Notice-->
                            <div
                                class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                                <!--begin::Icon-->
                                <i class="ki-duotone ki-information fs-2tx text-warning me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold">
                                        <div class="fs-6 text-gray-700">
                                            <strong
                                                class="me-1">تحذير!</strong>{{'من خلال تعديل اسم الصلاحية، قد يؤدي ذلك إلى تعطيل وظيفة صلاحيات النظام. يرجى التأكد من أنك متأكد تمامًا قبل المتابعة'}}
                                        </div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Notice-->
                            <!--end::Notice-->
                            <!--begin::Form-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mb-2">
                                        <span class="required">الادوار</span>
                                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                              data-bs-html="true"
                                              data-bs-content="Role names is required to be unique.">
															<i class="ki-duotone ki-information fs-7">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
															</i>
														</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select class="form-select form-select-solid" data-control="select2" data-mce-placeholder="حدد خياراً" name="role_id">
                                        <option></option>
                                        @foreach($roles as $role)
                                            <option
                                                value="{{$role->id}}" @selected($permission->role_id == $role->id)>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-semibold form-label mb-2">
                                        <span class="required">اسم الصلاحية</span>
                                        <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                              data-bs-html="true"
                                              data-bs-content="Permission names is required to be unique.">
															<i class="ki-duotone ki-information fs-7">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
															</i>
														</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" value="{{$permission->name}}"
                                           placeholder="" name="name"/>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
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
                                                    <span class="ms-1" data-bs-toggle="tooltip"
                                                          title="السماح بكل الصلاحيات للنظام">
																			<i class="ki-duotone ki-information-5 text-gray-500 fs-6">
																				<span class="path1"></span>
																				<span class="path2"></span>
																				<span class="path3"></span>
																			</i>
																		</span></td>
                                                <td>
                                                    <!--begin::Checkbox-->
                                                    <label
                                                        class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                        <input class="form-check-input" type="checkbox" value="1"
                                                               id="kt_roles_select_all"/>
                                                        <span class="form-check-label" for="kt_roles_select_all">تحديد الكل</span>
                                                    </label>
                                                    <!--end::Checkbox-->
                                                </td>
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Label-->
                                                <td class="text-gray-800">التصنيفات</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['category']['can-add'])) checked
                                                                   @endif  value="1"
                                                                   name="permissions[category][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['category']['can-edit'])) checked
                                                                   @endif name="permissions[category][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['category']['can-show'])) checked
                                                                   @endif value="1"
                                                                   name="permissions[category][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['category']['can-delete'])) checked
                                                                   @endif value="1"
                                                                   name="permissions[category][can-delete]"/>
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
                                                            <input class="form-check-input" type="checkbox"  @if(isset($permission['permissions']['admin']['can-add'])) checked
                                                                   @endif value="1" name="permissions[admin][can-add]" />
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"  @if(isset($permission['permissions']['admin']['can-edit'])) checked
                                                                   @endif value="1" name="permissions[admin][can-edit]" />
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"  @if(isset($permission['permissions']['admin']['can-show'])) checked
                                                                   @endif value="1" name="permissions[admin][can-show]" />
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"  @if(isset($permission['permissions']['admin']['can-delete'])) checked
                                                                   @endif value="1" name="permissions[admin][can-delete]" />
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
                                                <td class="text-gray-800">المستخدمين</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['user']['can-add'])) checked
                                                                   @endif name="permissions[user][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['user']['can-edit'])) checked
                                                                   @endif value="1" name="permissions[user][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['user']['can-show'])) checked
                                                                   @endif name="permissions[user][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['user']['can-delete'])) checked
                                                                   @endif name="permissions[user][can-delete]"/>
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
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['student']['can-add'])) checked
                                                                   @endif name="permissions[student][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['student']['can-edit'])) checked
                                                                   @endif value="1" name="permissions[student][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['student']['can-show'])) checked
                                                                   @endif name="permissions[student][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['student']['can-delete'])) checked
                                                                   @endif name="permissions[student][can-delete]"/>
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
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['teacher']['can-add'])) checked
                                                                   @endif name="permissions[teacher][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['teacher']['can-edit'])) checked
                                                                   @endif value="1" name="permissions[teacher][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['teacher']['can-show'])) checked
                                                                   @endif name="permissions[teacher][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['teacher']['can-delete'])) checked
                                                                   @endif name="permissions[teacher][can-delete]"/>
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
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['role']['can-add'])) checked
                                                                   @endif value="1" name="permissions[role][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['role']['can-edit'])) checked
                                                                   @endif value="1" name="permissions[role][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['role']['can-show'])) checked
                                                                   @endif value="1" name="permissions[role][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['role']['can-delete'])) checked
                                                                   @endif name="permissions[role][can-delete]"/>
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
                                                <td class="text-gray-800">الأقسام</td>
                                                <!--end::Label-->
                                                <!--begin::Input group-->
                                                <td>
                                                    <!--begin::Wrapper-->
                                                    <div class="d-flex">
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['section']['can-add'])) checked
                                                                   @endif value="1" name="permissions[section][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['section']['can-edit'])) checked
                                                                   @endif value="1" name="permissions[section][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['section']['can-show'])) checked
                                                                   @endif value="1" name="permissions[section][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox" value="1"
                                                                   @if(isset($permission['permissions']['section']['can-delete'])) checked
                                                                   @endif name="permissions[section][can-delete]"/>
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
                                                        <label
                                                            class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input"
                                                                   @if(isset($permission['permissions']['permission']['can-add'])) checked
                                                                   @endif type="checkbox" value="1"
                                                                   name="permissions[permission][can-add]"/>
                                                            <span class="form-check-label">اضافة</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input"
                                                                   @if(isset($permission['permissions']['permission']['can-edit'])) checked
                                                                   @endif type="checkbox" value="1"
                                                                   name="permissions[permission][can-edit]"/>
                                                            <span class="form-check-label">تعديل</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input"
                                                                   @if(isset($permission['permissions']['permission']['can-show'])) checked
                                                                   @endif type="checkbox" value="1"
                                                                   name="permissions[permission][can-show]"/>
                                                            <span class="form-check-label">عرض</span>
                                                        </label>
                                                        <!--end::Checkbox-->
                                                        <!--begin::Checkbox-->
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-5 me-lg-20">
                                                            <input class="form-check-input" type="checkbox"
                                                                   @if(isset($permission['permissions']['permission']['can-delete'])) checked
                                                                   @endif value="1"
                                                                   name="permissions[permission][can-delete]"/>
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
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
                                        <span class="indicator-label">حفظ التعديلات</span>
                                        <span class="indicator-progress">الرجاء الانتظار...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </form>
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>


@endsection
@section('scripts')
    <script src="{{asset('assets/js/custom/apps/users-management/permissions/update-permission.js')}}"></script>
@endsection
