@extends('cms.layout.master')
@section('toolbar-title','الملف الشخصي')
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','الملف الشخصي')
@section('style')
    <meta name="user_id" content="{{$user->id}}">
@endsection
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Container-->
        <div class="container-xxl" id="kt_content_container">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">ملخص</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#kt_user_view_overview_security">الامان</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_user_view_overview_events_and_logs_tab">السجلات والاحداث</a>
                        </li>
                        <!--end:::Tab item-->
                        <!--begin:::Tab item-->
                        <li class="nav-item ms-auto">
                            <!--begin::Action menu-->
                            <a href="#" class="btn btn-primary ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">الاجراءات
                                <i class="ki-duotone ki-down fs-2 me-0"></i></a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link px-5">التقارير</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link text-danger px-5">حذف المستخدم</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Menu-->
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->
                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card card-flush mb-6 mb-xl-12">
                                <!--begin::Card header-->
                                <div class="card-header mt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">معلومات عامة</h2>
                                        <div class="fs-6 fw-semibold text-muted"></div>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#kt_modal_update_details">
                                            <i class="ki-duotone ki-brush fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>تعديل</button>

                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body p-9 pt-4">
                                    <div class="card-body">
                                        <!--begin::Summary-->
                                        <!--begin::User Info-->
                                        <div class="d-flex flex-center flex-column py-5">
                                            <!--begin::Avatar-->
                                            @if(isset($user->avatar))
                                                <div class="symbol symbol-100px symbol-circle mb-7">
                                                    <img src="{{Storage::url($user->avatar)}}" alt="image" />
                                                </div>
                                            @else
                                                <div class="symbol symbol-100px symbol-circle mb-7">
                                                    <img src="{{asset('assets/media/svg/avatars/blank.svg')}}" alt="image" />
                                                </div>
                                            @endif

                                            <!--end::Avatar-->
                                            <!--begin::Name-->
                                            <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{$user->first_name .' '.$user->last_name}}</a>
                                            <!--end::Name-->
                                            <!--begin::Position-->
                                            <div class="mb-9">
                                                <!--begin::Badge-->
                                                <div class="badge badge-lg badge-light-primary d-inline">{{$user->role->name}}</div>
                                                <!--begin::Badge-->
                                            </div>
                                            <!--end::Position-->
                                        </div>
                                        <!--end::User Info-->
                                        <!--end::Summary-->
                                        <!--begin::Details toggle-->
                                        <div class="d-flex flex-stack fs-4 py-3">
                                            <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                                التفاصيل
                                                <span class="ms-2 rotate-180">
													<i class="ki-duotone ki-down fs-3"></i>
												</span></div>
                                        </div>
                                        <!--end::Details toggle-->
                                        <div class="separator"></div>
                                        <!--begin::Details content-->
                                        <div id="kt_user_view_details" class="collapse show">
                                            <div class="pb-5 fs-6">
                                                <!--begin::Details item-->
                                                <div class="fw-bold mt-5">#</div>
                                                <div class="text-gray-600">{{$user->id}}</div>
                                                <!--begin::Details item-->
                                                <!--begin::Details item-->
                                                <div class="fw-bold mt-5">البريد الالكتروني</div>
                                                <div class="text-gray-600">
                                                    <a href="#" class="text-gray-600 text-hover-primary">{{$user->email}}</a>
                                                </div>
                                                <!--begin::Details item-->
                                                <div class="fw-bold mt-5">الجنس:</div>
                                                @if($user->gender == 'male')
                                                    <div class="text-gray-600">ذكر</div>
                                                @else
                                                    <div class="text-gray-600">انثى</div>
                                                @endif
                                                <!--begin::Details item-->
                                                <!--begin::Details item-->
                                                <div class="fw-bold mt-5">الحالة:</div>
                                                @if($user->status == 'active')
                                                    <div class="badge badge-success">فعال</div>
                                                @else
                                                    <div class="badge badge-danger">غير فعال</div>
                                                @endif
                                                <!--begin::Details item-->
                                                <!--begin::Details item-->
                                                <div class="fw-bold mt-5">آخر دخول</div>
                                                <div class="text-gray-600">{{$user->last_login}}</div>
                                                <!--begin::Details item-->
                                            </div>
                                        </div>
                                        <!--end::Details content-->
                                    </div>

                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->

                        </div>
                        <!--end:::Tab pane-->
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade" id="kt_user_view_overview_security" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-12">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>الملف الشخصي</h2>
                                    </div>
                                    <!--end::Card title-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 pb-5">
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                            <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td>البريد الالكتروني</td>
                                                <td>{{$user->email}}</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_update_email">
                                                        <i class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>كلمة المرور</td>
                                                <td>******</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_update_password">
                                                        <i class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>الدور:</td>
                                                <td>{{$user->role->name}}</td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">
                                                        <i class="ki-duotone ki-pencil fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end:::Tab pane-->
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>جلسات الدخول</h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Filter-->
                                        <button type="button" class="btn btn-sm btn-flex btn-light-primary" id="kt_modal_sign_out_sesions">
                                            <i class="ki-duotone ki-entrance-right fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>تسجيل خروج من كافة الجلسات</button>
                                        <!--end::Filter-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 pb-5">
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                            <tr class="text-start text-muted text-uppercase gs-0">
                                                <th class="min-w-100px">#</th>
                                                <th class="min-w-100px">الموقع</th>
                                                <th>الاجهزة</th>
                                                <th>عنوان IP</th>
                                                <th class="min-w-125px">الوقت</th>
                                                <th class="min-w-70px">الاجراءات</th>
                                            </tr>
                                            </thead>
                                            <tbody class="fs-6 fw-semibold text-gray-600">
{{--                                            @foreach($user->sessions as $session)--}}
{{--                                            <tr>--}}
{{--                                                <td>{{$session->user_id}}</td>--}}
{{--                                                <td>Location</td>--}}
{{--                                                <td>{{$session->user_agent}}</td>--}}
{{--                                                <td>{{$session->ip_address}}</td>--}}
{{--                                                <td>{{$session->last_activity}}</td>--}}
{{--                                                <td>الجلسة الحالية</td>--}}
{{--                                            </tr>--}}
{{--                                            @endforeach--}}
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Logs</h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-sm btn-light-primary">
                                            <i class="ki-duotone ki-cloud-download fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Download Report</button>
                                        <!--end::Button-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fw-semibold text-gray-600 fs-6 gy-5" id="kt_table_users_logs">
                                            <tbody>
                                            <tr>
                                                <td class="min-w-70px">
                                                    <div class="badge badge-light-success">200 OK</div>
                                                </td>
                                                <td>POST /v1/invoices/in_7229_4615/payment</td>
                                                <td class="pe-0 text-end min-w-200px">10 Nov 2023, 6:43 am</td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-70px">
                                                    <div class="badge badge-light-warning">404 WRN</div>
                                                </td>
                                                <td>POST /v1/customer/c_64b77cbec7581/not_found</td>
                                                <td class="pe-0 text-end min-w-200px">22 Sep 2023, 10:30 am</td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-70px">
                                                    <div class="badge badge-light-danger">500 ERR</div>
                                                </td>
                                                <td>POST /v1/invoice/in_2768_3949/invalid</td>
                                                <td class="pe-0 text-end min-w-200px">10 Mar 2023, 8:43 pm</td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-70px">
                                                    <div class="badge badge-light-success">200 OK</div>
                                                </td>
                                                <td>POST /v1/invoices/in_2331_8703/payment</td>
                                                <td class="pe-0 text-end min-w-200px">21 Feb 2023, 10:10 pm</td>
                                            </tr>
                                            <tr>
                                                <td class="min-w-70px">
                                                    <div class="badge badge-light-warning">404 WRN</div>
                                                </td>
                                                <td>POST /v1/customer/c_64b77cbec7581/not_found</td>
                                                <td class="pe-0 text-end min-w-200px">15 Apr 2023, 8:43 pm</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Events</h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-sm btn-light-primary">
                                            <i class="ki-duotone ki-cloud-download fs-3">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>Download Report</button>
                                        <!--end::Button-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 text-gray-600 fw-semibold gy-5" id="kt_table_customers_events">
                                        <tbody>
                                        <tr>
                                            <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#SEP-45656</a>status has changed from
                                                <span class="badge badge-light-warning me-1">Pending</span>to
                                                <span class="badge badge-light-info">In Progress</span></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">19 Aug 2023, 9:23 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#KIO-45656</a>status has changed from
                                                <span class="badge badge-light-succees me-1">In Transit</span>to
                                                <span class="badge badge-light-success">Approved</span></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">19 Aug 2023, 10:30 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#WER-45670</a>is
                                                <span class="badge badge-light-info">In Progress</span></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">10 Mar 2023, 10:10 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#KIO-45656</a>status has changed from
                                                <span class="badge badge-light-succees me-1">In Transit</span>to
                                                <span class="badge badge-light-success">Approved</span></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">25 Oct 2023, 11:30 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#WER-45670</a>is
                                                <span class="badge badge-light-info">In Progress</span></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">15 Apr 2023, 6:43 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Emma Smith</a>has made payment to
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">20 Jun 2023, 6:43 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Melody Macy</a>has made payment to
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">19 Aug 2023, 5:30 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Sean Bean</a>has made payment to
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">24 Jun 2023, 2:40 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary me-1">#WER-45670</a>is
                                                <span class="badge badge-light-info">In Progress</span></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">25 Oct 2023, 8:43 pm</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Sean Bean</a>has made payment to
                                                <a href="#" class="fw-bold text-gray-900 text-hover-primary">#XRS-45670</a></td>
                                            <td class="pe-0 text-gray-600 text-end min-w-200px">15 Apr 2023, 11:05 am</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>
                        <!--end:::Tab pane-->
                    </div>
                    <!--end:::Tab content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->
            <!--begin::Modals-->

            <!--begin::Modal - Update users details-->
            <div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <div id="error-messages"></div>
                        <!--begin::Form-->
                        <form class="form" action="" method="POST" enctype="multipart/form-data" id="kt_modal_update_user_form">
                       @method('PUT')
                            @csrf
                            <!--begin::Modal header-->
                            <div class="modal-header" id="kt_modal_update_user_header">
                                <!--begin::Modal title-->
                                <h2 class="fw-bold">تعديل بيانات المستخدم</h2>
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
                            <div class="modal-body py-10 px-lg-17">
                                <!--begin::Scroll-->
                                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_user_header" data-kt-scroll-wrappers="#kt_modal_update_user_scroll" data-kt-scroll-offset="300px">
                                    <!--begin::User toggle-->
                                    <div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_user_info" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_user_info">معلومات المستخدم
                                        <span class="ms-2 rotate-180">
														<i class="ki-duotone ki-down fs-3"></i>
													</span></div>
                                    <!--end::User toggle-->
                                    <!--begin::User form-->
                                    <div id="kt_modal_update_user_user_info" class="collapse show">
                                        <!--begin::Input group-->
                                        <div class="mb-7">
                                            <!--begin::Label-->
                                            <label class="fs-6 fw-semibold mb-2">
                                                <span>الصورة</span>
                                                <span class="ms-1" data-bs-toggle="tooltip" title="Allowed file types: png, jpg, jpeg.">
																	<i class="ki-duotone ki-information fs-7">
																		<span class="path1"></span>
																		<span class="path2"></span>
																		<span class="path3"></span>
																	</i>
																</span>
                                            </label>
                                            <!--end::Label-->
                                            <!--begin::Image input wrapper-->
                                            <div class="mt-1">
                                                <!--begin::Image placeholder-->
                                                <style>.image-input-placeholder { background-image: url('{{asset('assets/media/svg/avatars/blank.svg')}}'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('{{asset('assets/media/svg/avatars/blank-dark.svg')}}'); }</style>
                                                <!--end::Image placeholder-->
                                                <!--begin::Image input-->
                                                <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                                    <!--begin::Preview existing avatar-->
                                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset('storage/'.$user->avatar)}}"></div>
                                                    <!--end::Preview existing avatar-->
                                                    <!--begin::Edit-->
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
                                                    <!--end::Edit-->
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
                                            </div>
                                            <!--end::Image input wrapper-->
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">الاسم الاول</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="name" id="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="ادخل الاسم بالكامل هنا" value="{{$user->first_name}}" />
                                            <!--end::Input-->
                                            <div id="name-error" class="error-message"></div>
                                        </div>
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">اسم العائلة</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="text" name="name" id="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="ادخل الاسم بالكامل هنا" value="{{$user->last_name}}" />
                                            <!--end::Input-->
                                            <div id="name-error" class="error-message"></div>
                                        </div>
{{--                                        <div class="fv-row mb-7">--}}
{{--                                            <!--begin::Label-->--}}
{{--                                            <label class="required fw-semibold fs-6 mb-2">اسم المستخدم</label>--}}
{{--                                            <!--end::Label-->--}}
{{--                                            <!--begin::Input-->--}}
{{--                                            <input type="text" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="ادخل اسم المستخدم" value="{{$user->user_name}}" />--}}
{{--                                            <!--end::Input-->--}}
{{--                                            <div id="user_name-error" class="error-message"></div>--}}
{{--                                        </div>--}}
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">البريد الالكتروني</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" value="{{$user->email}}" />
                                            <!--end::Input-->
                                            <div id="email-error" class="error-message"></div>
                                        </div>

                                        <div class="fv-row mb-7">
                                            <!--begin::Label-->
                                            <label class="required fw-semibold fs-6 mb-2">الهاتف</label>
                                            <!--end::Label-->
                                            <!--begin::Input-->
                                            <input type="tel" name="phone_number" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{trans('dashboard_trans.Phone')}}" value="{{$user->phone_number}}" />
                                            <!--end::Input-->
                                            <div id="phone_number-error" class="error-message"></div>

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
                                                        <input class="form-check-input" type="radio" value="male" @checked($user->gender === 'male') name="gender" id="gender_male" >
                                                        <label class="form-check-label" for="gender_male">ذكر</label>
                                                    </div>
                                                    <div class="form-check form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="radio" value="female"  @checked($user->gender === 'female') name="gender" id="gender_female">
                                                        <label class="form-check-label" for="gender_female">انثى</label>
                                                    </div>
                                                    <!--end::Radio-->
                                                </div>
                                            </div>
                                            <div id="gender-error" class="error-message"></div>

                                        </div>
                                        <div class="fv-row mb-7">
                                            <!--begin::Wrapper-->
                                            <div class="d-flex flex-stack">
                                                <!--begin::Label-->
                                                <div class="me-5">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold">الحالة: </label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <div class="fs-7 fw-semibold text-muted">تعيين الحالة يمكن المستخدم من الدخول الى النظام</div>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Label-->
                                                <!--begin::Switch-->
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <!--begin::Input-->
                                                    <input class="form-check-input" name="status" type="checkbox"  id="status" @checked($user->status == 'active')>
                                                    <!--end::Input-->
                                                </label>
                                                <!--end::Switch-->
                                            </div>
                                            <!--begin::Wrapper-->
                                            <div id="status-error" class="error-message"></div>

                                        </div>
                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">الغاء</button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit" >
                                    <span class="indicator-label">تأكيد</span>
                                    <span class="indicator-progress">الرجاء الانتظار...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Modal footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
            <!--end::Modal - Update users details-->
        </div>

        <!--begin::Modal - Update email-->
        <div class="modal fade" id="kt_modal_update_email" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">تحديث البريد الالكتروني</h2>
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
                        <form id="kt_modal_update_email_form" method="POST" class="form" action="#" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <!--begin::Notice-->
                            <!--begin::Notice-->
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                <!--begin::Icon-->
                                <i class="ki-duotone ki-information fs-2tx text-primary me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold">يرجى ملاحظة أنه مطلوب عنوان بريد إلكتروني صالح لإكمال التحقق من البريد الإلكتروني
                                        <div class="fs-6 text-gray-700">.</div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Notice-->
                            <!--end::Notice-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span class="required">البريد الالكتروني</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="example@cardio.com" name="email" value="{{$user->email}}" />
                                <!--end::Input-->
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">الغاء</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit" >
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
        <!--end::Modal - Update email-->
        <!--begin::Modal - Update password-->
        <div class="modal fade" id="kt_modal_update_password" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">تحديث كلمة المرور</h2>
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
                        <form id="kt_modal_update_password_form" class="form" method="POST" action="">
                            @method('PUT')
                            @csrf
                            <!--begin::Input group=-->
                            <div class="fv-row mb-10">
                                <label class="required form-label fs-6 mb-2">كلمة المرور الحالية</label>
                                <input class="form-control form-control-lg form-control-solid" id="current_password" type="password" placeholder="" name="current_password" autocomplete="off" />
                                <div id="current_password-error" class="error-message"></div>

                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row" data-kt-password-meter="true">
                                <!--begin::Wrapper-->
                                <div class="mb-1">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold fs-6 mb-2">كلمة مرور جديدة</label>
                                    <!--end::Label-->
                                    <!--begin::Input wrapper-->
                                    <div class="position-relative mb-3">
                                        <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" autocomplete="off" />
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
																<i class="ki-duotone ki-eye-slash fs-1">
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																	<span class="path4"></span>
																</i>
																<i class="ki-duotone ki-eye d-none fs-1">
																	<span class="path1"></span>
																	<span class="path2"></span>
																	<span class="path3"></span>
																</i>
															</span>
                                    </div>
                                    <!--end::Input wrapper-->
                                    <!--begin::Meter-->
                                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>
                                    <!--end::Meter-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Hint-->
                                <div class="text-muted">{{trans('dashboard_trans.Use 8 or more characters with a mix of letters, numbers & symbols')}}.</div>
                                <!--end::Hint-->
                                <div id="password-error" class="error-message"></div>
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-10">
                                <label class="form-label fw-semibold fs-6 mb-2">تأكيد كلمة المرور</label>
                                <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm_password" autocomplete="off" />
                                <div id="confirm_password-error" class="error-message"></div>

                            </div>
                            <!--end::Input group=-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">الغاء</button>
                                <button type="submit" class="btn btn-primary"  data-kt-users-modal-action="submit">
                                    <span class="indicator-label" >تأكيد</span>
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
        <!--end::Modal - Update password-->
        <!--begin::Modal - Update role-->
        <div class="modal fade"  id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">تحديث دور المستخدم</h2>
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
                        <form id="kt_modal_update_role_form" class="form" action="#" method="POST">
                            @method('PUT')
                            @csrf
                            <!--begin::Notice-->
                            <!--begin::Notice-->
                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                <!--begin::Icon-->
                                <i class="ki-duotone ki-information fs-2tx text-primary me-4">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-semibold">
                                        <div class="fs-6 text-gray-700">Please note that reducing a users role rank, that users will lose all privileges that was assigned to the previous role.</div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Notice-->
                            <!--end::Notice-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-5">
                                    <span class="required">حدد الدور</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input row-->
                                @foreach($roles as $role)
                                <div class="d-flex">
                                    <!--begin::Radio-->
                                    <div class="form-check form-check-custom form-check-solid">
                                        <!--begin::Input-->
                                        <input class="form-check-input me-3" name="role_id" value="{{$role->id}}" type="radio"  id="role_id" @checked($user->role_id == $role->id) />
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
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">الغاء</button>
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
        <!--end::Modal - Update role-->
        <!--end::Modals-->
        <!--end::Container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script src="{{asset('assets/js/custom/apps/user-management/users/view/update-details.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/view/update-email.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/view/update-password.js')}}"></script>
    <script src="{{asset('assets/js/custom/apps/user-management/users/view/update-role.js')}}"></script>

@endsection
