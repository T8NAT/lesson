@extends('cms.layout.master')
@section('toolbar-title','Lesson App')
@section('breadcrumb','لوحة التحكم')
    @section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">
            <!--begin::Row-->
            <div class="row g-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xl-12">
                    <!--begin::Mixed Widget 2-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 bg-primary py-5">
                            <h3 class="card-title fw-bold text-white">Lesson App</h3>
                            <div class="card-toolbar">
                                <!--begin::Menu-->
                                <button type="button" class="btn btn-sm btn-icon btn-color-white btn-active-white btn-active-color- border-0 me-n3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-category fs-6">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </button>
                                <!--begin::Menu 3-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3" data-kt-menu="true">
                                    <!--begin::Heading-->
                                    <div class="menu-item px-3">
                                        <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">اختصارات</div>
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{route('categories.create')}}" class="menu-link px-3">انشاء قسم</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{route('teachers.create')}}" class="menu-link flex-stack px-3">اضافة معلم
                                            <span class="ms-2" data-bs-toggle="tooltip" title="يمكنك اضافة معلم جديد">
																	<i class="ki-duotone ki-information fs-6">
																		<span class="path1"></span>
																		<span class="path2"></span>
																		<span class="path3"></span>
																	</i>
																</span></a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{route('categories.create')}}" class="menu-link px-3">انشاء قسم</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
                                        <a href="#" class="menu-link px-3">
                                            <span class="menu-title">المستخدمين</span>
                                            <span class="menu-arrow"></span>
                                        </a>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{route('admins.index')}}" class="menu-link px-3">المدراء</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{route('teachers.index')}}" class="menu-link px-3">المعلمين</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="" class="menu-link px-3">الطلاب</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu separator-->
                                            <div class="separator my-2"></div>
                                            <!--end::Menu separator-->
                                            <!--begin::Menu item-->
{{--                                            <div class="menu-item px-3">--}}
{{--                                                <div class="menu-content px-3">--}}
{{--                                                    <!--begin::Switch-->--}}
{{--                                                    <label class="form-check form-switch form-check-custom form-check-solid">--}}
{{--                                                        <!--begin::Input-->--}}
{{--                                                        <input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />--}}
{{--                                                        <!--end::Input-->--}}
{{--                                                        <!--end::Label-->--}}
{{--                                                        <span class="form-check-label text-muted fs-6">Recuring</span>--}}
{{--                                                        <!--end::Label-->--}}
{{--                                                    </label>--}}
{{--                                                    <!--end::Switch-->--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu sub-->
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3 my-1">
                                        <a href="#" class="menu-link px-3">الاعدادات</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu 3-->
                                <!--end::Menu-->
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body p-0">
                            <!--begin::Chart-->
                            <div class="mixed-widget-1-chart card-rounded-bottom bg-primary" data-kt-color="primary" style="height: 200px"></div>
                            <!--end::Chart-->
                            <!--begin::Stats-->
                            <div class="card-p mt-n20 position-relative">
                                <!--begin::Row-->
                                <div class="row g-0">
                                    <!--begin::Col-->
                                    <div class="col d-flex flex-column bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
                                        <i class="ki-duotone ki-chart-simple fs-2x text-warning my-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                        <a href="{{route('categories.index')}}" class="text-warning fw-semibold fs-6">الاقسام</a>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col d-flex flex-column bg-light-primary px-6 py-8 rounded-2 mb-7">
                                        <i class="ki-duotone ki-briefcase fs-2x text-primary my-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <a href="" class="text-primary fw-semibold fs-6">الالعاب</a>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row g-0">
                                    <!--begin::Col-->
                                    <div class="col d-flex flex-column bg-light-danger px-6 py-8 rounded-2 me-7">
                                        <i class="ki-duotone ki-abstract-26 fs-2x text-danger my-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <a href="{{route('teachers.index')}}" class="text-danger fw-semibold fs-6 mt-2">المعلمين</a>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col-->
                                    <div class="col d-flex flex-column bg-light-success px-6 py-8 rounded-2">
                                        <i class="ki-duotone ki-sms fs-2x text-success my-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <a href="#" class="text-success fw-semibold fs-6 mt-2">المدراء</a>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 2-->
                </div>
                <!--end::Col-->
                <div class="col-xl-6">
                    <!--begin::Tables Widget 4-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">المعلمين</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">اكثر من   معلم  جديد </span>
                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary active fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_4_tab_1">الشهر</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_4_tab_2">الاسبوع</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4" data-bs-toggle="tab" href="#kt_table_widget_4_tab_3">اليوم</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
{{--                        <div class="card-body py-3">--}}
{{--                            <div class="tab-content">--}}
{{--                                <!--begin::Tap pane-->--}}
{{--                                <div class="tab-pane fade show active" id="kt_table_widget_4_tab_1">--}}
{{--                                    <!--begin::Table container-->--}}
{{--                                    <div class="table-responsive">--}}
{{--                                        <!--begin::Table-->--}}
{{--                                        <table class="table align-middle gs-0 gy-3">--}}
{{--                                            <!--begin::Table head-->--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th class="p-0 w-50px"></th>--}}
{{--                                                <th class="p-0 min-w-150px"></th>--}}
{{--                                                <th class="p-0 min-w-140px"></th>--}}
{{--                                                <th class="p-0 min-w-120px"></th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}
{{--                                            <!--end::Table head-->--}}
{{--                                            <!--begin::Table body-->--}}
{{--                                            <tbody>--}}
{{--                                            @foreach(getTeachersBerMonth() as $teacher)--}}
{{--                                                <tr>--}}
{{--                                                    <td>--}}
{{--                                                        <div class="symbol symbol-50px">--}}
{{--                                                            <img src="{{Storage::url($teacher->avatar)}}" alt="" />--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{$teacher->name}}</a>--}}
{{--                                                        <span class="text-muted fw-semibold d-block fs-7">{{$teacher->email}}</span>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <span class="badge badge-light-primary">{{$teacher->role->name}}</span>--}}
{{--                                                    </td>--}}
{{--                                                    <td class="text-end">--}}
{{--                                                        <a href="{{route('authors.show',$teacher->id)}}" class="btn btn-icon btn-light-twitter btn-sm me-3">--}}
{{--                                                            <i class="ki-duotone ki-user-edit fs-4">--}}
{{--                                                                <span class="path1"></span>--}}
{{--                                                                <span class="path2"></span>--}}
{{--                                                            </i>--}}
{{--                                                        </a>--}}
{{--                                                        --}}{{--                                                <a href="#" class="btn btn-icon btn-light-facebook btn-sm">--}}
{{--                                                        --}}{{--                                                    <i class="ki-duotone ki-trash fs-4">--}}
{{--                                                        --}}{{--                                                        <span class="path1"></span>--}}
{{--                                                        --}}{{--                                                        <span class="path2"></span>--}}
{{--                                                        --}}{{--                                                    </i>--}}
{{--                                                        --}}{{--                                                </a>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            @endforeach--}}
{{--                                            </tbody>--}}
{{--                                            <!--end::Table body-->--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
{{--                                    <!--end::Table-->--}}
{{--                                </div>--}}
{{--                                <!--end::Tap pane-->--}}
{{--                                <!--begin::Tap pane-->--}}
{{--                                <div class="tab-pane fade" id="kt_table_widget_4_tab_2">--}}
{{--                                    <!--begin::Table container-->--}}
{{--                                    <div class="table-responsive">--}}
{{--                                        <!--begin::Table-->--}}
{{--                                        <table class="table align-middle gs-0 gy-3">--}}
{{--                                            <!--begin::Table head-->--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th class="p-0 w-50px"></th>--}}
{{--                                                <th class="p-0 min-w-150px"></th>--}}
{{--                                                <th class="p-0 min-w-140px"></th>--}}
{{--                                                <th class="p-0 min-w-120px"></th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}
{{--                                            <!--end::Table head-->--}}
{{--                                            <!--begin::Table body-->--}}
{{--                                            <tbody>--}}
{{--                                           @foreach(getTeachersBerWeak() as $teacher)--}}
{{--                                               <tr>--}}
{{--                                                   <td>--}}
{{--                                                       <div class="symbol symbol-50px">--}}
{{--                                                           <img src="{{Storage::url($teacher->avatar)}}" alt="" />--}}
{{--                                                       </div>--}}
{{--                                                   </td>--}}
{{--                                                   <td>--}}
{{--                                                       <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{$teacher->name}}</a>--}}
{{--                                                       <span class="text-muted fw-semibold d-block fs-7">{{$teacher->email}}</span>--}}
{{--                                                   </td>--}}
{{--                                                   <td>--}}
{{--                                                       <span class="badge badge-light-primary">{{$teacher->role->name}}</span>--}}
{{--                                                   </td>--}}
{{--                                                   <td class="text-end">--}}
{{--                                                       <a href="{{route('authors.show',$teacher->id)}}" class="btn btn-icon btn-light-twitter btn-sm me-3">--}}
{{--                                                           <i class="ki-duotone ki-user-edit fs-4">--}}
{{--                                                               <span class="path1"></span>--}}
{{--                                                               <span class="path2"></span>--}}
{{--                                                           </i>--}}
{{--                                                       </a>--}}
{{--                                                       --}}{{--                                                <a href="#" class="btn btn-icon btn-light-facebook btn-sm">--}}
{{--                                                       --}}{{--                                                    <i class="ki-duotone ki-trash fs-4">--}}
{{--                                                       --}}{{--                                                        <span class="path1"></span>--}}
{{--                                                       --}}{{--                                                        <span class="path2"></span>--}}
{{--                                                       --}}{{--                                                    </i>--}}
{{--                                                       --}}{{--                                                </a>--}}
{{--                                                   </td>--}}
{{--                                               </tr>--}}
{{--                                           @endforeach--}}
{{--                                            </tbody>--}}
{{--                                            <!--end::Table body-->--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
{{--                                    <!--end::Table-->--}}
{{--                                </div>--}}
{{--                                <!--end::Tap pane-->--}}
{{--                                <!--begin::Tap pane-->--}}
{{--                                <div class="tab-pane fade" id="kt_table_widget_4_tab_3">--}}
{{--                                    <!--begin::Table container-->--}}
{{--                                    <div class="table-responsive">--}}
{{--                                        <!--begin::Table-->--}}
{{--                                        <table class="table align-middle gs-0 gy-3">--}}
{{--                                            <!--begin::Table head-->--}}
{{--                                            <thead>--}}
{{--                                            <tr>--}}
{{--                                                <th class="p-0 w-50px"></th>--}}
{{--                                                <th class="p-0 min-w-150px"></th>--}}
{{--                                                <th class="p-0 min-w-140px"></th>--}}
{{--                                                <th class="p-0 min-w-120px"></th>--}}
{{--                                            </tr>--}}
{{--                                            </thead>--}}
{{--                                            <!--end::Table head-->--}}
{{--                                            <!--begin::Table body-->--}}
{{--                                            <tbody>--}}
{{--                                            @foreach(getTeachersBerDay() as $teacher)--}}
{{--                                                <tr>--}}
{{--                                                    <td>--}}
{{--                                                        <div class="symbol symbol-50px">--}}
{{--                                                            <img src="{{Storage::url($teacher->avatar)}}" alt="" />--}}
{{--                                                        </div>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <a href="#" class="text-dark fw-bold text-hover-primary mb-1 fs-6">{{$teacher->name}}</a>--}}
{{--                                                        <span class="text-muted fw-semibold d-block fs-7">{{$teacher->email}}</span>--}}
{{--                                                    </td>--}}
{{--                                                    <td>--}}
{{--                                                        <span class="badge badge-light-primary">{{$teacher->role->name}}</span>--}}
{{--                                                    </td>--}}
{{--                                                    <td class="text-end">--}}
{{--                                                        <a href="{{route('authors.show',$teacher->id)}}" class="btn btn-icon btn-light-twitter btn-sm me-3">--}}
{{--                                                            <i class="ki-duotone ki-user-edit fs-4">--}}
{{--                                                                <span class="path1"></span>--}}
{{--                                                                <span class="path2"></span>--}}
{{--                                                            </i>--}}
{{--                                                        </a>--}}
{{--                                                        --}}{{--                                                <a href="#" class="btn btn-icon btn-light-facebook btn-sm">--}}
{{--                                                        --}}{{--                                                    <i class="ki-duotone ki-trash fs-4">--}}
{{--                                                        --}}{{--                                                        <span class="path1"></span>--}}
{{--                                                        --}}{{--                                                        <span class="path2"></span>--}}
{{--                                                        --}}{{--                                                    </i>--}}
{{--                                                        --}}{{--                                                </a>--}}
{{--                                                    </td>--}}
{{--                                                </tr>--}}
{{--                                            @endforeach--}}
{{--                                            </tbody>--}}
{{--                                            <!--end::Table body-->--}}
{{--                                        </table>--}}
{{--                                    </div>--}}
{{--                                    <!--end::Table-->--}}
{{--                                </div>--}}
{{--                                <!--end::Tap pane-->--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <!--end::Body-->
                    </div>
                    <!--end::Tables Widget 4-->
                </div>
                <div class="col-xl-6">
                    <!--begin::Tables Widget 5-->
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">آخر الطلاب</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">اكثر من طالب جديد </span>
                            </h3>
                            <div class="card-toolbar">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 me-1 active" data-bs-toggle="tab" href="#kt_table_widget_5_tab_1">الشهر</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_5_tab_2">الاسبوع</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-secondary fw-bold px-4" data-bs-toggle="tab" href="#kt_table_widget_5_tab_3">اليوم</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <div class="tab-content">
                                <!--begin::Tap pane-->
                                <div class="tab-pane fade show active" id="kt_table_widget_5_tab_1">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                            <tr class="border-0">
                                                <th class="p-0 w-50px"></th>
                                                <th class="p-0 min-w-150px"></th>
                                                <th class="p-0 min-w-140px"></th>
                                                <th class="p-0 min-w-110px"></th>
                                                <th class="p-0 min-w-50px"></th>
                                            </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>

                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Tap pane-->
                                <!--begin::Tap pane-->
                                <div class="tab-pane fade" id="kt_table_widget_5_tab_2">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                            <tr class="border-0">
                                                <th class="p-0 w-50px"></th>
                                                <th class="p-0 min-w-150px"></th>
                                                <th class="p-0 min-w-140px"></th>
                                                <th class="p-0 min-w-110px"></th>
                                                <th class="p-0 min-w-50px"></th>
                                            </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>

                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Tap pane-->
                                <!--begin::Tap pane-->
                                <div class="tab-pane fade" id="kt_table_widget_5_tab_3">
                                    <!--begin::Table container-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 gy-4">
                                            <!--begin::Table head-->
                                            <thead>
                                            <tr class="border-0">
                                                <th class="p-0 w-50px"></th>
                                                <th class="p-0 min-w-150px"></th>
                                                <th class="p-0 min-w-140px"></th>
                                                <th class="p-0 min-w-110px"></th>
                                                <th class="p-0 min-w-50px"></th>
                                            </tr>
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody>

                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Tap pane-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Tables Widget 5-->
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Content container-->
    </div>
@endsection
@section('scripts')

@endsection
