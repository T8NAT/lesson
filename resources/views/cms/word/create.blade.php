@extends('cms.layout.master')
@section('toolbar-title','اضافة مفردات جديدة')
@section('breadcrumb','كافة المفردات')
@section('sub-breadcrumb','اضافة مفردات جديدة')
@section('content')
    <!--begin::Heading-->
    <div class="card-border mb-10 text-center">
        <!--begin::Title-->
        <h1 class="mb-3">اضافة مفردات جديدة</h1>
        <!--end::Title-->
        <!--begin::Description-->
        <div class="text-gray-400 fw-bold fs-5">يمكنك تصفح قائمة المفردات من
            <a href="{{ route('words.index') }}" class="fw-bolder link-primary">هنا</a>.
        </div>
        <!--end::Description-->
    </div>
    <!--end::Heading-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            {{-- Display validation errors if any --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="kt_add_word_form" action="{{ route('words.store') }}" enctype="multipart/form-data" method="POST" class="form d-flex flex-column flex-lg-row" data-kt-redirect="{{ route('words.create') }}">
                @csrf
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Category-->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{'القسم'}}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <select class="form-select mb-2" data-control="select2" name="category_id" data-hide-search="true" data-placeholder="حدد القسم" required>
                                <option></option>
                            </select>
                            <div class="text-muted fs-7">{{'تعيين القسم للمفردات.'}}</div>
                            @error('category_id')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Category-->

                    <!--begin::Images List (Optional Info) -->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>الصور المتاحة</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0" style="max-height: 200px; overflow-y: auto;">
                            {{-- Make sure $images is passed from the controller to the view --}}
                            @if(isset($images) && $images->count() > 0)
                                <ul class="list-unstyled">
                                    @foreach($images as $key=>$image)
                                        <li class="fs-7 mb-1" title="{{ $image->image }}">
                                            <img src="{{ Storage::url($image->image) }}" alt="img" width="20" height="20" class="me-2">
                                            ({{ $image->name }})
                                            {{-- You might want a shorter display name/description for images later --}}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted fs-7">لا توجد صور مضافة بعد. <a href="#">إضافة صور</a></div> {{-- Link to image upload page --}}
                            @endif
                        </div>
                    </div>
                    <!--end::Images List-->
                </div>
                <!--end::Aside column-->

                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::Vocabulary Items-->
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{'المفردات والصور المرتبطة'}}</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="form-group row border-top pt-5 mb-3 " data-index="__INDEX__">
                                <div class="col-md-5 fv-row">
                                    <label class="form-label required">{{'المفردة (الكلمة)'}}:</label>
                                    <input type="text" name="items[__INDEX__][word]" class="form-control mb-2" placeholder="{{'أدخل الكلمة'}}" required/>
                                    @error('items.__INDEX__.word')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-5 fv-row">
                                    <label class="form-label">{{'الصورة المرتبطة (اختياري)'}}:</label>
                                    <select name="items[__INDEX__][image_id]" class="form-select item-image-select" data-control="select2" data-placeholder="اختر صورة..." data-allow-clear="true">
                                        <option></option>
                                        @if(isset($images))
                                            @foreach($images as $key => $image)
                                                <option value="{{ $image->id }}">{{ $key+1 }} - {{ Str::limit(basename($image->name), 20) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('items.__INDEX__.image_id')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-5 fv-row">
                                    <label class="form-label">{{'الاصوات المرتبطة (اختياري)'}}:</label>
                                    <select class="form-select mb-2" name="items[__INDEX__][audio_id]" data-control="select2" data-hide-search="true" data-placeholder="اختر صوت..."  data-allow-clear="true">
                                        <option></option>
                                        @if(isset($audios))
                                            @foreach($audios as $key => $audio)
                                                <option value="{{ $audio->id }}">{{ $key+1 }} - {{ Str::limit(basename($audio->name), 20) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('items.__INDEX__.audio_id')
                                    <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-light-danger remove-vocabulary-item">
                                        <i class="ki-duotone ki-trash fs-5"></i>{{'حذف'}}
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                            </div>
                            <button type="button" class="btn btn-light-primary mt-5">
                                <i class="ki-duotone ki-plus fs-3"></i>{{'إضافة مفردة جديدة'}}
                            </button>
                            @error('items')
                            <div class="text-danger fs-7 mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Vocabulary Items-->

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('words.index') }}" id="kt_add_word_cancel" class="btn btn-light me-5">{{'الغاء'}}</a>
                        <button type="submit" class="btn btn-primary" id="kt_add_word_submit">
                            <span class="indicator-label">{{'حفظ المفردات'}}</span>
                            <span class="indicator-progress">{{'الرجاء الانتظار'}}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
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
     <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/ar.min.js"></script>
     <script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
     <script src="{{asset('assets/js/cms/words/save-word.js')}}"></script>

     <script>
         const categories = {
             get: "{{ route('getCategories') }}",
         };
     </script>
@endsection
