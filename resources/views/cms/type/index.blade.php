@extends('cms.layout.master')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('toolbar-title','انواع الالعاب')
@section('breadcrumb','كافة الانواع')
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Category-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" data-kt-type-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="{{'بحث الانواع'}}" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
{{--                    @if (userHasPermission('game', 'can-add'))--}}
                        <div class="card-toolbar">
                            <!--begin::Add customer-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_type">
                                <i class="ki-duotone ki-plus fs-2"></i>{{'اضافة نوع جديد'}}</button>
                            <!--end::Add customer-->
                        </div>
{{--                    @endif--}}
                    <!--end::Card toolbar-->
                </div>
                <!--begin::Modal - Add task-->
                <div class="modal fade" id="kt_modal_add_type" tabindex="-1" aria-hidden="true">
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-650px">
                        <!--begin::Modal content-->
                        <div class="modal-content">
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_add_type_header">
                                <!--begin::Modal title-->
                                <h2 class="fw-bold">{{'اضافة نوع جديد'}}</h2>
                                <!--end::Modal title-->
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-types-modal-action="close">
                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <!--end::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body px-5 my-7">
                                <!--begin::Form-->
                                <form id="kt_cms_add_type_form" class="form" action="{{route('types.store')}}" data-kt-redirect="{{route('types.index')}}" enctype="multipart/form-data" method="POST">
                                    @csrf
                                    <!--begin::Scroll-->
                                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_type_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_type_header" data-kt-scroll-wrappers="#kt_modal_add_type_scroll" data-kt-scroll-offset="300px">

                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">الاسم بالكامل</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="قم بادخال الاسم النوع هنا" value="{{old('name')}}" />
                                            <!--end::Input-->
                                            <div id="name-error" class="error-message"></div>

                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                    <!--end::Scroll-->
                                    <!--begin::Actions-->
                                    <div class="text-center pt-10">
                                        <button type="reset" class="btn btn-light me-3" data-kt-types-modal-action="cancel">إلغاء</button>
                                        <button type="submit" class="btn btn-primary" data-kt-types-modal-action="submit">
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
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_type_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_type_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-250px">{{'النوع'}}</th>
                            <th class="text-end min-w-70px">{{'الاجراءات'}}</th>
                        </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                        <!--end::Table body-->
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Category-->
        </div>
        <!--end::Content container-->
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{asset('assets/js/cms/type/type.js')}}"></script>
        <script src="{{asset('assets/js/cms/type/add-type.js')}}"></script>
    <!--end::Custom Javascript-->
@endsection
