@extends('cms.layout.master')
@section('toolbar-title','كافة المدراء')
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','قائمة المدراء')
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
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-admin-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="{{'بحث المدراء'}}" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-admin-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="ki-duotone ki-filter fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{'فلتر'}}</button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bold">{{'خيارات الفلتر'}}</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5" data-kt-admin-table-filter="form">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-semibold">الادوار:</label>
                                        <select class="form-select form-select-solid fw-bold" data-control="select2" data-kt-select2="false" data-placeholder="{{'حدد خياراً'}}" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->name}}">{{ $role->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-admin-table-filter="reset">{{'اعادة'}}</button>
                                        <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-admin-table-filter="filter">{{'تطبيق'}}</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                            <!--begin::Export-->
                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_admins">
                                <i class="ki-duotone ki-exit-up fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{'تصدير'}}</button>
                            <!--end::Export-->
                            @if (userHasPermission('admin', 'can-add'))
                            <!--begin::Add users-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_admin">
                                <i class="ki-duotone ki-plus fs-2"></i>{{'اضافة مدير جديد'}}</button>
                            <!--end::Add users-->
                            @endif
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none" data-kt-admin-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-admin-table-select="selected_count"></span>تحديد</div>
                            <button type="button" class="btn btn-danger" data-kt-admin-table-select="delete_selected">حذف المحدد</button>
                        </div>
                        <!--end::Group actions-->
                        <!--begin::Modal - Adjust Balance-->
                        <div class="modal fade" id="kt_modal_export_admins" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">{{'تصدير المدراء'}}</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-admins-modal-action="close">
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
                                        <form id="kt_modal_export_admins_form" class="form" action="#">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold form-label mb-2">{{'تحديد الدور'}}:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select name="role" data-control="select2"  data-placeholder="{{'حدد خياراً'}}" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                    <option></option>
                                                    @foreach($roles as $role)
                                                        <option value="{{$role->name}}">{{ $role->name}}</option>
                                                    @endforeach
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="required fs-6 fw-semibold form-label mb-2">{{'حدد تنسيق التصدير'}}:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select name="format" data-control="select2" data-placeholder="{{'حدد خياراً'}}" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                    <option></option>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="text-center">
                                                <button type="reset" class="btn btn-light me-3" data-kt-admins-modal-action="cancel">{{'تجاهل'}}</button>
                                                <button type="submit" class="btn btn-primary" data-kt-admins-modal-action="submit">
                                                    <span class="indicator-label">{{'تأكيد'}}</span>
                                                    <span class="indicator-progress">{{'الرجاء الانتظار'}}...
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
                        <!--end::Modal - New Card-->
                        <!--begin::Modal - Add task-->
                        <div class="modal fade" id="kt_modal_add_admin" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header" id="kt_modal_add_admin_header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">{{'اضافة مدير جديد'}}</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-admins-modal-action="close">
                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    <!--end::Modal header-->
                                    <!--begin::Modal body-->
                                    <div class="modal-body px-5 my-7">
                                        <!--begin::Form-->
                                        <form id="kt_cms_add_admin_form" class="form" action="{{route('admins.store')}}" data-kt-redirect="{{route('admins.index')}}" enctype="multipart/form-data" method="POST">
                                            @csrf
                                            <!--begin::Scroll-->
                                            <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="d-block fw-semibold fs-6 mb-5">{{'الصورة'}}</label>
                                                    <!--end::Label-->
                                                    <!--begin::Image placeholder-->
                                                    <style>.image-input-placeholder { background-image: url('{{asset('assets/media/svg/files/blank-image.svg')}}'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
                                                    <!--end::Image placeholder-->
                                                    <!--begin::Image input-->
                                                    <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                                        <!--begin::Preview existing avatar-->
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset('assets/media/svg/files/blank-image.svg')}});"></div>
                                                        <!--end::Preview existing avatar-->
                                                        <!--begin::Label-->
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                            <i class="ki-duotone ki-pencil fs-7">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            <!--begin::Inputs-->
                                                            <input type="file" name="avatar" id="avatar" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                            <!--end::Inputs-->
                                                        </label>
                                                        <!--end::Label-->
                                                        <!--begin::Cancel-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
																			<i class="ki-duotone ki-cross fs-2">
																				<span class="path1"></span>
																				<span class="path2"></span>
																			</i>
																		</span>
                                                        <!--end::Cancel-->
                                                        <!--begin::Remove-->
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
																			<i class="ki-duotone ki-cross fs-2">
																				<span class="path1"></span>
																				<span class="path2"></span>
																			</i>
																		</span>
                                                        <!--end::Remove-->
                                                    </div>
                                                    <!--end::Image input-->
                                                    <!--begin::Hint-->
                                                    <div class="form-text">نوع الملفات المسموح بها: png, jpg, jpeg.</div>
                                                    <!--end::Hint-->
                                                    <div id="avatar-error" class="error-message"></div>
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">الاسم بالكامل</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="قم بادخال الاسم بالكامل هنا" value="{{old('name')}}" />
                                                    <!--end::Input-->
                                                    <div id="name-error" class="error-message"></div>

                                                </div>
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">اسم المستخدم</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="text" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="قم بادخال اسم المتخدم" value="{{old('user_name')}}" />
                                                    <!--end::Input-->
                                                    <div id="user_name-error" class="error-message"></div>

                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">البريد الالكتروني</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" value="{{old('email')}}" />
                                                    <!--end::Input-->
                                                    <div id="email-error" class="error-message"></div>
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">كلمة المرور</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="password" name="password" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="قم بادخال كلمة المرور هنا" value="{{old('password')}}" />
                                                    <!--end::Input-->
                                                    <div id="password-error" class="error-message"></div>

                                                </div>
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">العنوان</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="text" name="address" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="قم بادخال العنوان هنا" value="{{old('address')}}" />
                                                    <!--end::Input-->
                                                    <div id="address-error" class="error-message"></div>

                                                </div>
                                                <div class="fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">رقم الهاتف</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <input type="tel" name="mobile_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="ادخل رقم الهاتف" value="{{old('mobile_number')}}" />
                                                    <!--end::Input-->
                                                    <div id="mobile_number-error" class="error-message"></div>

                                                </div>
                                                <!--end::Input group-->
                                                <div class="row fv-row mb-7">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-2">
                                                        <span>الجنس:</span>
                                                        <span class="ms-1" data-bs-toggle="tooltip"  data-kt-initialized="1"></span>
                                                    </label>
                                                    <!--end::Label-->
                                                    <div class="col-md-9">
                                                        <div class="d-flex mt-3">
                                                            <!--begin::Radio-->
                                                            <div class="form-check form-check-custom form-check-solid me-5">
                                                                <input class="form-check-input" type="radio" value="male" name="gender" id="gender_male" >
                                                                <label class="form-check-label" for="gender_male">ذكر</label>
                                                            </div>
                                                            <div class="form-check form-check-custom form-check-solid">
                                                                <input class="form-check-input" type="radio" value="female" name="gender" id="gender_female">
                                                                <label class="form-check-label" for="gender_female">انثى</label>
                                                            </div>
                                                            <!--end::Radio-->
                                                        </div>
                                                    </div>
                                                    <div id="gender-error" class="error-message"></div>
                                                </div>
                                                <div class="fv-row mb-7">
                                                    <!--begin::Status-->
                                                    <label class="required fw-semibold fs-6 mb-2">الحالة</label>
                                                        <div class="card-body pt-0">
                                                            <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="{{'حدد خياراً'}}" id="kt_ecommerce_add_category_status_select">
                                                                <option></option>
                                                                <option value="active">{{'فعال'}}</option>
                                                                <option value="inactive">{{'غير فعال'}}</option>
                                                                <option value="blocked">محظور</option>
                                                            </select>
                                                            <!--end::Select2-->
                                                            <!--begin::Description-->
                                                            <div class="text-muted fs-7">{{'تعيين حالة المدير.'}}</div>
                                                            <!--end::Description-->
                                                        </div>
                                                    <div id="status-error" class="error-message"></div>
                                                </div>
                                                <!--begin::Input group-->
                                                <div class="mb-5">
                                                    <!--begin::Label-->
                                                    <label class="required fw-semibold fs-6 mb-5">الدور</label>
                                                    <!--end::Label-->
                                                    <!--begin::Roles-->
                                                    <!--begin::Input row-->
                                                    @foreach($roles as $role)
                                                        <div class="d-flex fv-row">
                                                            <!--begin::Radio-->
                                                            <div class="form-check form-check-custom form-check-solid">
                                                                <!--begin::Input-->
                                                                <input class="form-check-input me-3" name="role_id" type="radio" value="{{$role->id}}" id="kt_modal_update_role_option_0" checked='checked' />
                                                                <!--end::Input-->
                                                                <!--begin::Label-->
                                                                <label class="form-check-label" for="kt_modal_update_role_option_0">
                                                                    <div class="fw-bold text-gray-800">{{$role->name}}</div>
                                                                    <div class="text-gray-600">{{$role->description}}</div>
                                                                </label>
                                                                <!--end::Label-->
                                                            </div>
                                                            <!--end::Radio-->
                                                        </div>
                                                        <!--end::Input row-->
                                                        <div class='separator separator-dashed my-5'></div>
                                                    @endforeach
                                                    <!--end::Roles-->
                                                    <div id="role_id-error" class="error-message"></div>

                                                </div>
                                                <!--end::Input group-->
                                            </div>
                                            <!--end::Scroll-->
                                            <!--begin::Actions-->
                                            <div class="text-center pt-10">
                                                <button type="reset" class="btn btn-light me-3" data-kt-admins-modal-action="cancel">إلغاء</button>
                                                <button type="submit" class="btn btn-primary" data-kt-admins-modal-action="submit">
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
                        <!--end::Modal - Add task-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_admins">
                        <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_admins .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-200px">اسم المدير</th>
                            <th class="min-w-125px">الدور</th>
                            <th class="min-w-125px">اخر دخول</th>
                            <th class="min-w-125px">الحالة</th>
                            <th class="min-w-125px">تاريخ الانضمام</th>
                            <th class="text-end min-w-100px">الاجراءات</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('assets/js/cms/user-management/admin/admin-table.js')}}"></script>
    <script src="{{asset('assets/js/cms/user-management/admin/export-admins.js')}}"></script>
    <script src="{{asset('assets/js/cms/user-management/admin/add-admin.js')}}"></script>
    <script src="{{asset('assets/js/cms/user-management/admin/admin-search.js')}}"></script>
@endsection
