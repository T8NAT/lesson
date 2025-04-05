@extends('cms.layout.master')

@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    {{-- Add custom style if needed for visual enhancements --}}
    <style>
        /* Optional: Ensure image fits well in the table cell */
        #kt_game_table .symbol img {
            object-fit: contain; /* Or 'cover', depending on desired look */
        }
        /* Ensure filter dropdowns have some spacing */
        .filter-dropdown {
            min-width: 150px;
        }
    </style>
@endsection

@section('toolbar-title','إدارة الألعاب') {{-- More descriptive title --}}
@section('breadcrumb','قائمة الألعاب')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
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
                            <input type="text" data-kt-game-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="{{'بحث عن لعبة...'}}" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons (Optional)-->
                        {{-- <div id="kt_datatable_example_1_export" class="d-none"></div> --}}
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Filters-->
                        <div class="w-100 mw-200px">
                            <!--begin::Select2-->
                            {{-- **Important**: Populate these options from your Controller/View --}}
                            <select class="form-select form-select-solid filter-dropdown" data-control="select2" data-hide-search="true" data-placeholder="فلترة حسب النوع" data-kt-game-filter="type">
                                <option></option>
                                <option value="all">كل الأنواع</option>
                                @foreach($gameTypes ?? [] as $type) {{-- Pass $gameTypes from controller --}}
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <!--end::Select2-->
                        </div>
                        <div class="w-100 mw-200px">
                            {{-- **Important**: Populate these options from your Controller/View --}}
                            <select class="form-select form-select-solid filter-dropdown" data-control="select2" data-hide-search="true" data-placeholder="فلترة حسب القسم" data-kt-game-filter="category">
                                <option></option>
                                <option value="all">كل الأقسام</option>
                                @foreach($categories ?? [] as $category) {{-- Pass $categories from controller --}}
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-100 mw-150px">
                            <select class="form-select form-select-solid filter-dropdown" data-control="select2" data-hide-search="true" data-placeholder="فلترة حسب الحالة" data-kt-game-filter="status">
                                <option></option>
                                <option value="all">كل الحالات</option>
                                <option value="active">نشطة</option>
                                <option value="inactive">غير نشطة</option>
                            </select>
                        </div>
                        <!--end::Filters-->

                        {{-- @if (userHasPermission('game', 'can-add')) --}}
                        <!--begin::Add game-->
                        <a href="{{ route('games.create') }}" class="btn btn-primary">
                            <i class="ki-duotone ki-plus fs-2"></i> {{'اضافة لعبة جديدة'}}
                        </a>
                        <!--end::Add game-->
                        {{-- @endif --}}

                        <!--begin::Export dropdown (Optional)-->
                        {{-- Add export functionality if needed using Metronic's datatable examples --}}
                        <!--begin::Export-->
                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                            <i class="ki-duotone ki-exit-up fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>تصدير</button>
                        <!--end::Export-->
                        <!--begin::Modal - Adjust Balance-->
                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                            <!--begin::Modal dialog-->
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <!--begin::Modal content-->
                                <div class="modal-content">
                                    <!--begin::Modal header-->
                                    <div class="modal-header">
                                        <!--begin::Modal title-->
                                        <h2 class="fw-bold">تصدير الالعاب</h2>
                                        <!--end::Modal title-->
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
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
                                        <form id="kt_modal_export_users_form" class="form" action="#">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="required fs-6 fw-semibold form-label mb-2">حدد تنسيق التصدير:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select name="format" data-control="select2" data-placeholder="حدد خياراً" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                    <option></option>
                                                    <option value="excel">Excel</option>
                                                    <option value="pdf">PDF</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="text-center">
                                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">تجاهل</button>
                                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
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
                        <!--end::Modal - New Card-->
                        <!--end::Export dropdown-->

                    </div>
                    <!--end::Card toolbar-->

                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none" data-kt-game-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-game-table-select="selected_count"></span> عنصر محدد
                        </div>
                        {{-- @if (userHasPermission('game', 'can-delete')) --}}
                        <button type="button" class="btn btn-danger" data-kt-game-table-select="delete_selected">
                            حذف المحدد
                        </button>
                        {{-- @endif --}}
                    </div>
                    <!--end::Group actions-->

                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_game_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_game_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-80px text-center">{{'صورة رمزية'}}</th>
                            <th class="min-w-200px">{{'اسم اللعبة'}}</th>
                            <th class="min-w-150px">{{'نوع اللعبة'}}</th>
                            <th class="min-w-200px">{{'الأقسام'}}</th>
                            <th class="min-w-100px text-center">{{'الحالة'}}</th>
                            <th class="text-end min-w-100px">{{'الاجراءات'}}</th>
                        </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
@endsection

@section('scripts')
    <script src="{{asset('assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('assets/js/cms/games/games.js')}}"></script>
@endsection
