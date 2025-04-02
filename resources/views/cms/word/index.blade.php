@extends('cms.layout.master')
@section('style')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('assets/plugins/custom/datatables/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('toolbar-title','الكلمات')
@section('breadcrumb','كافة الكلمات')
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
                            <input type="text" data-kt-word-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="{{'بحث الكلمات'}}" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    {{--                    @if (userHasPermission('game', 'can-add'))--}}
                    <div class="card-toolbar">
                        <!--begin::Add customer-->
                        <a href="{{ route('words.create') }}" class="btn btn-primary">{{'اضافة كلمات'}}</a>
                        <!--end::Add customer-->
                    </div>
                    {{--                    @endif--}}
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_word_table">
                        <thead>
                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_word_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="min-w-250px">{{'الكلمات'}}</th>
                            <th class="min-w-250px">{{'القسم'}}</th>
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
    <script src="{{asset('assets/js/cms/words/words.js')}}"></script>
    {{--    <script src="{{asset('assets/js/custom/utilities/modals/users-search.js')}}"></script>--}}
    <!--end::Custom Javascript-->
@endsection
