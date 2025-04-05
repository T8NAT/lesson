@extends('cms.layout.master')
@section('toolbar-title', 'لوحة تحكم تطبيق الألعاب')
@section('breadcrumb', 'لوحة التحكم الرئيسية')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid">

            <!--begin::Row: Key Statistics -->
            <div class="row g-5 g-xl-8 mb-8">
                <!-- Stat: Students -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch bg-light-success">
                        <div class="card-body d-flex align-items-center ps-xl-8">
                            <i class="ki-duotone ki-profile-user fs-2x text-success me-5"> <span class="path1"></span> <span class="path2"></span> <span class="path3"></span> </i>
                            <div class="d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $studentCount ?? 0 }}</span>
                                <span class="text-gray-700 fw-semibold fs-6">طالب مسجل</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stat: Teachers -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch bg-light-info">
                        <div class="card-body d-flex align-items-center ps-xl-8">
                            <i class="ki-duotone ki-user-tie fs-2x text-info me-5"> <span class="path1"></span> <span class="path2"></span> <span class="path3"></span> <span class="path4"></span> <span class="path5"></span> </i>
                            <div class="d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $teacherCount ?? 0 }}</span>
                                <span class="text-gray-700 fw-semibold fs-6">معلم متاح</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stat: Games -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch bg-light-warning">
                        <div class="card-body d-flex align-items-center ps-xl-8">
                            <i class="ki-duotone ki-game fs-2x text-warning me-5"> <span class="path1"></span> <span class="path2"></span> </i>
                            <div class="d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $gameCount ?? 0 }}</span>
                                <span class="text-gray-700 fw-semibold fs-6">لعبة متاحة</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Stat: Vocabulary Items -->
                <div class="col-xl-3 col-md-6">
                    <div class="card card-xl-stretch bg-light-danger">
                        <div class="card-body d-flex align-items-center ps-xl-8">
                            <i class="ki-duotone ki-book-open fs-2x text-danger me-5"> <span class="path1"></span> <span class="path2"></span> </i>
                            <div class="d-flex flex-column">
                                <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2">{{ $vocabularyCount ?? 0 }}</span>
                                <span class="text-gray-700 fw-semibold fs-6">مفردة تعليمية</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row: Key Statistics -->


            <!--begin::Row: Content Management & Activity -->
            <div class="row g-5 g-xl-8">
                <!--begin::Col: Quick Actions & Content Links -->
                <div class="col-xl-5">
                    <div class="card card-xl-stretch mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">إدارة المحتوى</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">روابط سريعة لإدارة محتوى التطبيق</span>
                            </h3>
                            <div class="card-toolbar">
                                <!--begin::Menu-->
                                <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    <i class="ki-duotone ki-plus-square fs-2"> <span class="path1"></span> <span class="path2"></span> <span class="path3"></span> </i>
                                </button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_641ac41e7792c">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">إضافة جديد</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Menu separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Menu separator-->
                                    <!--begin::Form-->
                                    <div class="px-7 py-5">
                                        <div class="mb-5">
                                            <a href="{{route('games.create')}}" class="btn btn-light-primary w-100 mb-2"> <i class="ki-duotone ki-game fs-5 me-2"></i> إضافة لعبة</a> {{-- Update route --}}
                                            <a href="{{route('categories.create')}}" class="btn btn-light-info w-100 mb-2"> <i class="ki-duotone ki-folder fs-5 me-2"></i> إضافة قسم</a>
                                            <a href="{{route('words.create')}}" class="btn btn-light-danger w-100 mb-2"> <i class="ki-duotone ki-book-open fs-5 me-2"></i> إضافة مفردات</a> {{-- Assuming route is words.create --}}
                                            <a href="{{route('images.create')}}" class="btn btn-light-warning w-100 mb-2"> <i class="ki-duotone ki-file-up fs-5 me-2"></i> إضافة صور</a> {{-- Add route for image management --}}
                                            {{-- <a href="#" class="btn btn-light-success w-100 mb-2"> <i class="ki-duotone ki-audio-square fs-5 me-2"></i> إضافة صوتيات</a> --}} {{-- If needed later --}}
                                        </div>
                                        <div class="separator border-gray-200 mb-5"></div>
                                        <div class="mb-2">
                                            <a href="{{route('teachers.create')}}" class="btn btn-light-dark w-100 mb-2"> <i class="ki-duotone ki-user-tie fs-5 me-2"></i> إضافة معلم</a>
                                            <a href="{{route('students.create')}}" class="btn btn-light-dark w-100"> <i class="ki-duotone ki-profile-user fs-5 me-2"></i> إضافة طالب</a> {{-- Add route for student management --}}
                                        </div>
                                    </div>
                                    <!--end::Form-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Menu-->
                            </div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <div class="d-flex flex-column">
                                <a href="{{route('games.index')}}" class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info p-6 mb-5">
                                    <i class="ki-duotone ki-game fs-2x text-info me-5"> <span class="path1"></span> <span class="path2"></span> </i>
                                    <span class="fs-4 fw-bold">إدارة الألعاب</span>
                                </a>

                                <a href="{{ route('categories.index') }}" class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning p-6 mb-5">
                                    <i class="ki-duotone ki-element-11 fs-2x text-warning me-5"> <span class="path1"></span> <span class="path2"></span> <span class="path3"></span> <span class="path4"></span> </i>
                                    <span class="fs-4 fw-bold">إدارة الأقسام ({{ $categoryCount ?? 0 }})</span>
                                </a>

                                <a href="{{ route('words.index') }}" class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger p-6 mb-5">
                                    <i class="ki-duotone ki-book-open fs-2x text-danger me-5"> <span class="path1"></span> <span class="path2"></span> </i>
                                    <span class="fs-4 fw-bold">إدارة المفردات ({{ $vocabularyCount ?? 0 }})</span>
                                </a>

                                <a href="{{route('images.index')}}" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success p-6 mb-5">
                                    <i class="ki-duotone ki-file-up fs-2x text-success me-5"> <span class="path1"></span> <span class="path2"></span> <span class="path3"></span> </i>
                                    <span class="fs-4 fw-bold">إدارة الصور</span>
                                </a>
{{--                                 <a href="#" class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary p-6"> <i class="ki-duotone ki-audio-square fs-2x text-primary me-5"> <span class="path1"></span> <span class="path2"></span> </i> <span class="fs-4 fw-bold">إدارة الصوتيات</span> </a>   If needed--}}
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
                <!--end::Col-->

                <!--begin::Col: Recent Activity -->
                <div class="col-xl-7">
                    <!--begin::Tables Widget 9-->
                    <div class="card card-xl-stretch mb-5 mb-xl-8">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">آخر المسجلين</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">آخر 5 طلاب ومعلمين</span>
                            </h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <!--begin::Table container-->
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <!--begin::Table head-->
                                    <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">المستخدم</th>
                                        <th class="min-w-140px">الدور</th>
                                        <th class="min-w-120px">تاريخ التسجيل</th>
                                        <th class="min-w-100px text-end">الحالة</th>
                                    </tr>
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody>
                                    {{-- ***** YOU NEED TO PASS $recentUsers from the controller ***** --}}
                                    {{-- $recentUsers should be a collection combining latest 5 students and teachers, sorted by creation date --}}
                                    @forelse($recentUsers ?? [] as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-45px me-5">
                                                        {{-- Assuming a generic avatar or based on role --}}
                                                        @if(isset($user->avatar))
                                                            <img src="{{ Storage::url($user->avatar) }}" alt=""/>
                                                        @else
                                                            <div class="symbol-label fs-2 fw-semibold {{ $user->role_name == 'Teacher' ? 'bg-light-info text-info' : 'bg-light-success text-success' }}"> {{-- Adjust logic based on how you identify roles --}}
                                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-start flex-column">
                                                        <a href="#" class="text-dark fw-bold text-hover-primary fs-6">{{ $user->name ?? 'N/A' }}</a>
                                                        <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $user->email ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->role->name == 'Teacher') {{-- Adjust condition --}}
                                                <span class="badge badge-light-info">معلم</span>
                                                @elseif($user->role->name == 'Student') {{-- Adjust condition --}}
                                                <span class="badge badge-light-success">طالب</span>
                                                @else
                                                    <span class="badge badge-light-secondary">{{ $user->role->name ?? 'غير معروف' }}</span>
                                                @endif
                                            </td>
                                            <td class="text-muted fw-semibold">
                                                {{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}
                                            </td>
                                            <td class="text-end">
                                                {{-- You can add status indicators here (e.g., Active, Pending) --}}
                                                @if($user->status == 'active')
                                                    <span class="badge badge-light-primary">نشط</span>
                                                @elseif($user->status == 'pending')
                                                    <span class="badge badge-light-warning">معلق</span>
                                                @else
                                                    <span class="badge badge-light-secondary">{{ $user->status ?? ''}}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">لا يوجد مستخدمون جدد حالياً.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Table container-->
                        </div>
                        <!--begin::Body-->
                    </div>
                    <!--end::Tables Widget 9-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row: Content Management & Activity -->

            <!-- OPTIONAL: Row for Charts or More Lists -->
            {{--
            <div class="row g-5 g-xl-8">
                <div class="col-xl-6">
                     -- Chart Widget --
                </div>
                 <div class="col-xl-6">
                     -- Another List/Table Widget (e.g., Most popular games) --
                </div>
            </div>
            --}}

        </div>
        <!--end::Content container-->
    </div>
@endsection

@section('scripts')
@endsection
