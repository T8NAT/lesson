@extends('cms.layout.master')
@section('toolbar-title','تعديل بيانات المدير')
@section('breadcrumb','لوحة التحكم')
@section('sub-breadcrumb','تعديل بيانات المدير'.': '.$admin->user_name)
@section('content')
    <!--begin::Card-->
    <div class="g-5 g-xl-8">
        <div class="col-xl-12">
            <!--begin::List Widget 6-->
            <div class="card card-xl-stretch mb-5 mb-xl-8">
                <!--begin::Header-->
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body pt-5">
                    <form action="{{route('admins.update',$admin->id)}}" id="kt_cms_edit_admin_form" data-kt-redirect="{{route('admins.index')}}"  method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <!--begin::Heading-->
                    <div class="mb-13 text-center">
                        <!--begin::Title-->
                        <h1 class="mb-3">تعديل بيانات المدير</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <div class="text-gray-400 fw-bold fs-5 mb-10">يمكنك تصفح قائمة المدراء من
                            <a href="{{ route('admins.index') }}" class="fw-bolder link-primary">هنا</a>.
                        </div>
                        <!--end::Description-->
                        <div class="col-md-12">
                            <!--begin::Label-->
                            <label class="fs-6 fw-semibold mb-2">
                                <span class="ms-1" data-bs-toggle="tooltip" title="الملفات المسموح بها: png, jpg, jpeg.">
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
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{Storage::url($admin->avatar)}}"></div>
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

                    </div>

                    <!--end::Heading-->
                        <div class="row">
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">معلومات عامة</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">الاسم الاول</label>
                                            <input type="text" name="first_name" value="{{$admin->first_name}}"
                                                   class="form-control @error('first_name') is-invalid @enderror" required="">
                                            @error('name')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">اسم العائلة</label>
                                            <input type="text" name="last_name" value="{{$admin->last_name}}"
                                                   class="form-control @error('last_name') is-invalid @enderror" required="">
                                            @error('name')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">العنوان</label>
                                            <input type="text" name="address" value="{{$admin->address}}" class="form-control ">
                                            @error('address')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">الهاتف</label>
                                            <input type="tel" name="phone" value="{{$admin->phone}}"
                                                   class="form-control @error('phone') is-invalid @enderror ">
                                            @error('phone')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">الجنس:</label>
                                            <div class="form-check form-check-inline mb-5">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                                       value="male" @if($admin->gender === 'male') checked @endif>
                                                <label class="form-check-label" for="gender_male">ذكر</label>

                                                <div class="form-check form-check-inline ms-10">
                                                    <input class="form-check-input" type="radio" name="gender" id="gender_female"
                                                           value="female" @if($admin->gender === 'female') checked @endif>
                                                    <label class="form-check-label" for="gender_female">انثى</label>
                                                </div>
                                            </div>

                                            @error('gender')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">معلومات تسجيل الدخول</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">البريد الالكتروني</label>
                                            <input type="email" value="{{$admin->email}}" name="email"
                                                   class="form-control @error('email') is-invalid @enderror" required="">
                                            @error('email')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">كلمة المرور</label>
                                            <input type="password" name="password" class="form-control" >
                                        </div>
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">الادوار</label>
                                            <select class="form-select" data-control="select2" data-mce-placeholder="قم باختيار الدور" name="role_id" required="">
                                                <option></option>
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}" @selected($admin->role_id == $role->id)>{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 d-flex flex-column mb-8 fv-row">
                                            <label class="col-lg-6 col-form-label fw-semibold fs-6">الحالة:</label>
                                            <select class="form-select" data-control="select2" data-mce-placeholder="قم باختيار الحالة" name="status" required="">
                                                <option></option>
                                                <option value="active" @selected($admin->status == 'active') >فعال</option>
                                                <option value="inactive" @selected($admin->status == 'inactive') >غير فعال</option>
                                                <option value="blocked" @selected($admin->status == 'blocked') >محظور</option>
                                            </select>
                                            @error('status')
                                            <span class="text-danger" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-center py-6 px-9">
                            <button type="reset" class="btn btn-white btn-active-light-primary me-2">
                                مسح البيانات
                            </button>

                            <button type="submit"  class="btn btn-primary" id="kt_ecommerce_edit_category_submit">
                                <span class="indicator-label">{{'حفظ التعديلات'}}</span>
                                <span class="indicator-progress">{{'الرجاء الانتظار'}}...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{asset('assets/js/cms/user-management/admin/edit-admin.js')}}"></script>

@endsection
