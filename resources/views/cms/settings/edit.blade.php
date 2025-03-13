@extends('cms.layout.master')
@section('title', 'الاعدادت')
@section('title','إعدادات التطبيق ')
@section('toolbar-title','الإعدادات')
@section('breadcrumb','التطبيق')
@section('sub-breadcrumb','ضبط اعداد التطبيق')
@section('content')
    @if(session()->has('alert-type'))
        <div class="alert {{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div id="kt_app_content" class="app-content flex-column-fluid">

        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card shadow-sm ">
                <div class="card-header text-center  text-white" style="background-color: #5dd0aa">
                    <h3 class="card-title">إعدادات التطبيق</h3>
                </div>
                <form id="kt_add_setting_form" action="{{ route('settings.update',$setting->id) }}" enctype="multipart/form-data" method="POST" class="form p-4" data-kt-redirect="{{route('settings.index')}}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-5">
                        <!--begin::Col-->
                        <div class="col-xl-3">
                            <div class="fs-6 fw-bold mt-2 mb-3">الشعار</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{asset('assets/media/avatars/blank.png')}})">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-125px h-125px bgi-position-center" style="background-size: 100%; background-image: url({{Storage::url($setting->logo)}})"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="" data-bs-original-title="Change avatar">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="logo" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="logo">
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->
                                <!--begin::Cancel-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="" data-bs-original-title="Cancel avatar">
															<i class="bi bi-x fs-2"></i>
														</span>
                                <!--end::Cancel-->
                                <!--begin::Remove-->
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="" data-bs-original-title="Remove avatar">
															<i class="bi bi-x fs-2"></i>
														</span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->
                            <!--begin::Hint-->
                            <div class="form-text">انواع الملفات المسموح بها: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label">ايقونة التطبيق</label>
                            <input type="file" name="favicon"  class="form-control" accept=".png, .svg,">
                            <input type="hidden" name="favicon">

                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">اسم التطبيق</label>
                            <input type="text" name="name" value="{{ $setting->name }}" class="form-control" placeholder="اسم التطبيق">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">رابط التطبيق</label>
                            <input type="url" name="url" value="{{ $setting->url }}" class="form-control" placeholder="رابط التطبيق">
                        </div>
                    </div>
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">الهاتف</label>
                            <input type="tel" name="phone" value="{{ $setting->phone }}" class="form-control" placeholder="رقم الهاتف">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ $setting->email }}" class="form-control" placeholder="البريد الإلكتروني">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">وصف عن الموقع</label>
                        <textarea name="about" class="form-control" rows="4" placeholder="وصف الموقع">{{ $setting->about }}</textarea>
                    </div>
{{--                    <div class="accordion mt-4" id="metaSettings">--}}
{{--                        <div class="accordion-item">--}}
{{--                            <h2 class="accordion-header">--}}
{{--                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#metaInfo" aria-expanded="true">--}}
{{--                                    إعدادات ميتا--}}
{{--                                </button>--}}
{{--                            </h2>--}}
{{--                            <div id="metaInfo" class="accordion-collapse collapse show">--}}
{{--                                <div class="accordion-body">--}}
{{--                                    <label class="form-label">وصف ميتا</label>--}}
{{--                                    <textarea name="meta_description" class="form-control" placeholder="وصف ميتا">{{ $setting->meta_description }}</textarea>--}}
{{--                                    <label class="form-label mt-2">الكلمات المفتاحية</label>--}}
{{--                                    <textarea name="meta_keyword" class="form-control" placeholder="الكلمات المفتاحية">{{ $setting->meta_keyword }}</textarea>--}}
{{--                                    <label class="form-label mt-2">(Alt)نص بديل للصورة</label>--}}
{{--                                    <textarea name="alt" class="form-control" placeholder="نص بديل للصورة">{{ $setting->alt }}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="accordion mt-4 mb-10" id="socialAccounts">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#socialInfo" aria-expanded="true">
                                    حسابات التواصل الاجتماعي
                                </button>
                            </h2>
                            <div id="socialInfo" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <label class="form-label">LinkedIn</label>
                                    <input type="url" name="linkedin" value="{{$setting->linkedin}}" class="form-control" placeholder="رابط LinkedIn">
                                    <label class="form-label mt-2">Facebook</label>
                                    <input type="url" name="facebook" value="{{ $setting->facebook }}" class="form-control" placeholder="رابط Facebook">
                                    <label class="form-label mt-2">Instagram</label>
                                    <input type="url" name="instagram" value="{{ $setting->instagram }}" class="form-control" placeholder="رابط Instagram">
                                    <label class="form-label mt-2">Twitter (X)</label>
                                    <input type="url" name="x" value="{{ $setting->x }}" class="form-control" placeholder="رابط X">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('settings.index') }}" id="kt_add_setting_cancel" class="btn btn-light me-5">{{'الغاء'}}</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit"  class="btn btn-primary" id="kt_add_setting_submit">
                            <span class="indicator-label">{{'حفظ التعديلات'}}</span>
                            <span class="indicator-progress">{{'الرجاء الانتظار'}}...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/cms/save-setting.js')}}"></script>
@endsection
