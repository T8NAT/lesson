@extends('cms.layout.master')
@section('toolbar-title','اضافة مرحلة جديدة')
@section('breadcrumb','كافة المراحل')
@section('sub-breadcrumb','اضافة مرحلة جديدة')
    @section('content')
        <!--begin::Heading-->
        <div class="card-border mb-13 text-center">
            <!--begin::Title-->
            <h1 class="mb-3">اضافة مرحلة جديدة</h1>
            <!--end::Title-->
            <!--begin::Description-->
            <div class="text-gray-400 fw-bold fs-5">يمكنك تصفح قائمة المراحل من
                <a href="{{route('levels.index')}}" class="fw-bolder link-primary">هنا</a>.
            </div>
            <!--end::Description-->
        </div>
        <!--end::Heading-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <form id="kt_add_level_form" action="{{ route('levels.store') }}" enctype="multipart/form-data" method="POST" class="form d-flex flex-column flex-lg-row" data-kt-redirect="{{route('levels.create')}}">
                 @csrf
                    <!--begin::Aside column-->
                    <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                        <!--begin::Game-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>{{'الالعاب'}}</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Select2-->
                                <select class="form-select mb-2" name="game_id[]" multiple data-control="select2" data-hide-search="true" data-placeholder="{{'حدد لعبة'}}" >
                                    <option></option>
                                </select>
                                <!--end::Select2-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">{{'اختيار اللعبة.'}}</div>
                                <!--end::Description-->
                                @error('game_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Game-->
                        <!--begin::Category-->
                        <div class="card card-flush py-4">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>{{'الاقسام'}}</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <!--begin::Select2-->
                                <select class="form-select mb-2" name="category_id" data-control="select2" data-hide-search="true" data-placeholder="{{'حدد القسم'}}" >
                                    <option></option>
                                </select>
                                <!--end::Select2-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">{{'اختيار القسم.'}}</div>
                                <!--end::Description-->
                                @error('category_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Category-->
                        <!--begin::Words-->
                        <div class="card card-flush py-4" id="words_section" style="display:none;">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>الكلمات</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <select class="form-select mb-2" name="word_id[]" id="words_select"  multiple data-control="select2" data-placeholder="اختر الكلمات المناسبة للمرحلة">
                                    <option></option>
                                </select>
                                <div class="text-muted fs-7">اختر الكلمات الخاصة بالمرحلة.</div>
                                @error('word_id')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <!--end::Words-->

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
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{'اسم المرحلة'}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="name" id="name"  class="form-control mb-2" placeholder="{{'قم بادخال اسم المرحلة هنا'}}" value="{{old('name')}}" />
                                    <!--end::Input-->
                                    <!--begin::Description-->
                                    <div class="text-muted fs-7">{{'يجب أن يكون اسم المرحلة فريدًا ومن المستحسن أن يكون فريدًا.'}}</div>
                                    <!--end::Description-->
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{'رقم المرحلة'}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" name="level_number" id="level_number"  class="form-control mb-2" placeholder="{{'قم بادخال رقم المرحلة هنا'}}" value="{{old('level_number')}}" />
                                    <!--end::Input-->
                                    @error('level_number')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{'النقاط المستحقة'}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="number" name="points_reward" id="points_reward"  class="form-control mb-2" placeholder="{{'قم بادخال عدد النقاط المستحقة هنا'}}" value="{{old('points_reward')}}" />
                                    <!--end::Input-->
                                    @error('points_reward')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row">
                                    <!--begin::Label-->
                                    <label class="form-label">{{'الوصف'}}</label>
                                    <!--end::Label-->
                                    <!--begin::Editor-->
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
                                    <!--end::Editor-->
                                    <!--begin::Description-->
                                    <div class="text-muted fs-7">{{'قم بتعيين وصف للقسم لتحسين الرؤية.'}}</div>
                                    <!--end::Description-->
                                    @error('description')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                                <!--end::Input group-->
                                <div class="row mb-0">
                                    <!--begin::Label-->
                                    <label class="col-lg-4 col-form-label fw-semibold fs-6">تفعيل المرحلة</label>
                                    <!--begin::Label-->
                                    <!--begin::Label-->
                                    <div class="col-lg-8 d-flex align-items-center">
                                        <div class="form-check form-check-solid form-switch form-check-custom fv-row">
                                            <input class="form-check-input w-45px h-30px" type="checkbox" name="is_active" id="is_active" checked="checked">
                                            <label class="form-check-label" for="allowmarketing"></label>
                                        </div>
                                    </div>
                                    <!--begin::Label-->
                                </div>
                            </div>
                            <!--end::Card header-->
                        </div>
                        <div class="d-flex justify-content-end">
                            <!--begin::Button-->
                            <a href="{{ route('levels.index') }}" id="kt_add_level_cancel" class="btn btn-light me-5">{{'الغاء'}}</a>
                            <!--end::Button-->
                            <!--begin::Button-->
                            <button type="submit"  class="btn btn-primary" id="kt_add_level_submit">
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
    <script src="{{asset('assets/js/cms/level/save-level.js')}}"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/ar.min.js"></script>

    <script>
        const games = {
            get: "{{ route('getGames') }}",
        };
        const categories = {
            get:"{{route('getCategories')}}"
        }

        const words = {
            get: "{{ route('getWordsByCategory') }}",
        };
        var selectedWords = [];
    </script>
@endsection
