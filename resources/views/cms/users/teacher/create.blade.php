@extends('cms.layout.master')
@section('toolbar-title','إضافة معلم جديد')
@section('breadcrumb','كافة المعلمين')
@section('sub-breadcrumb','إضافة معلم جديد')
@section('content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <form id="kt_cms_add_post_form" action="{{ route('authors.store') }}" enctype="multipart/form-data" method="POST" class="form d-flex flex-column flex-lg-row" data-kt-redirect="{{route('posts.create')}}">
                @csrf
                @method('POST')
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Thumbnail settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{'صورة المعلم'}}</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body text-center pt-0">
                            <!--begin::Image input-->
                            <!--begin::Image input placeholder-->
                            <style>.image-input-placeholder { background-image: url('{{asset('assets/media/svg/files/blank-image.svg')}}'); } [data-bs-theme="dark"] .image-input-placeholder { background-image: url('assets/media/svg/files/blank-image-dark.svg'); }</style>
                            <!--end::Image input placeholder-->
                            <!--begin::Image input-->
                            <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3" data-kt-image-input="true">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <!--end::Preview existing avatar-->
                                <!--begin::Label-->
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                    <!--begin::Icon-->
                                    <i class="ki-duotone ki-pencil fs-7">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    <!--end::Icon-->
                                    <!--begin::Inputs-->
                                    <input type="file" name="image" accept=".png, .jpg, .jpeg" />
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
                            <!--begin::Description-->
                            <div class="text-muted fs-7">{{'قم بتعيين صورة شخصية للمحرر. يتم قبول ملفات الصور *.png و*.jpg و*.jpeg فقط'}}</div>
                            <!--end::Description-->
                            @error('image')
                            <span class="text-danger" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Thumbnail settings-->

                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{'عام'}}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">

                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2" data-select2-id="select2-data-122-1fyv">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>الاسم بالكامل</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control" name="name" placeholder="الاسم بالكامل" value="{{old('name')}}">
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>اسم المستخدم</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control" name="user_name" placeholder="اسم المستخدم" value="{{old('user_name')}}">
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                            </div>

                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2" data-select2-id="select2-data-122-1fyv">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>البريد الإلكتروني</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control" name="email" placeholder="البريد الإلكتروني" value="{{old('email')}}">
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>كلمة المرور</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="password" class="form-control form-control" name="password" placeholder="كلمة المرور">
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                            </div>

                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2" data-select2-id="select2-data-122-1fyv">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="row fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="required fw-semibold fs-6 mb-2">
                                            <span>الجنس:</span>
                                            <span class="ms-1" data-bs-toggle="tooltip" data-kt-initialized="1"></span>
                                        </label>
                                        <!--end::Label-->
                                        <div class="col-md-9">
                                            <div class="d-flex mt-3">
                                                <!--begin::Radio-->
                                                <div class="form-check form-check-custom form-check-solid me-5">
                                                    <input class="form-check-input" type="radio" value="male" checked name="gender" id="gender_male">
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
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Label-->
                                            <div class="me-5">
                                                <!--begin::Label-->
                                                <label class="fs-6 fw-semibold">الحالة: </label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <div class="fs-7 fw-semibold text-muted">عليك تعيين حالة المحرر ليتمكن من الدخول الى النظام أو الحظر منه</div>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Label-->
                                            <!--begin::Switch-->
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <!--begin::Input-->
                                                <input class="form-check-input" name="status" type="checkbox" id="status" checked="checked">
                                                <!--end::Input-->
                                            </label>
                                            <!--end::Switch-->
                                        </div>
                                        <!--begin::Wrapper-->
                                        <div id="status-error" class="error-message"></div>

                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                            </div>

                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2" data-select2-id="select2-data-122-1fyv">
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>رقم الهاتف</span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="number" class="form-control form-control" name="phone_number" placeholder="رقم الهاتف" value="{{old('phone_number')}}">
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold form-label mt-3">
                                            <span>الأقسام</span>
                                        </label>
                                        <!--end::Label-->
                                        <select class="form-select mb-2" name="category_id[]" multiple  data-allow-clear="true"
                                              data-control="select2"  data-placeholder="{{ 'حدد الأقسام' }}">
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="text-danger pb-3">{{ 'تحديد الأقسام المسموح للمحرر بالنشر فيها' }}</div>

                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                            </div>

                            <!--end::Input group-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                    <!--begin::Meta options-->
                    <!--end::Meta options-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('authors.index') }}" id="kt_cms_add_post_cancel" class="btn btn-light me-5">{{'الغاء'}}</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit"  class="btn btn-primary" id="kt_cms_add_post_submit">
                            <span class="indicator-label">{{'انشاء'}}</span>
                            <span class="indicator-progress">{{'الرجاء الانتظار'}}...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </form>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
@endsection
