@extends('cms.layout.master')
@section('toolbar-title','اضافة كلمات جديد')
@section('breadcrumb','كافة الكلمات')
@section('sub-breadcrumb','اضافة كلمات جديدة')
@section('content')
    <!--begin::Heading-->
    <div class="card-border mb-13 text-center">
        <!--begin::Title-->
        <h1 class="mb-3">اضافة كلمات جديدة</h1>
        <!--end::Title-->
        <!--begin::Description-->
        <div class="text-gray-400 fw-bold fs-5">يمكنك تصفح قائمة الكلمات من
            <a href="{{route('words.index')}}" class="fw-bolder link-primary">هنا</a>.
        </div>
        <!--end::Description-->
    </div>
    <!--end::Heading-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <form id="kt_add_word_form" action="{{ route('words.store') }}" enctype="multipart/form-data" method="POST" class="form d-flex flex-column flex-lg-row" data-kt-redirect="{{route('words.create')}}">
                @csrf
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::main category-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{'قسم الكلمات'}}</h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <div class="rounded-circle w-15px h-15px"></div>
                            </div>
                            <!--begin::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Select2-->
                            <select class="form-select mb-2 select2-hidden-accessible" data-control="select2" name="category_id" data-hide-search="true" aria-hidden="true"  data-placeholder="حدد خياراً">
                                <option></option>
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">{{'تعيين القسم للكلمات.'}}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::main category-->
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::Word options-->
                                            <!--begin::Word options (Without FormRepeater)-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{'الكلمات المفتاحية'}}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <div id="word-container">
                                <div class="form-group row word-row">
                                    <div class="col-md-10">
                                        <label class="form-label">{{'كلمة'}}:</label>
                                        <input type="text" name="words[]" class="form-control mb-2 mb-md-0" placeholder="{{'أدخل كلمة'}}"/>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-light-danger mt-3 mt-md-8 remove-word">
                                            <i class="ki-duotone ki-trash fs-5"></i>{{'حذف'}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light-primary mt-3" id="add-word">
                                <i class="ki-duotone ki-plus fs-3"></i>{{'إضافة كلمة'}}
                            </button>
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Word options-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('words.index') }}" id="kt_add_word_cancel" class="btn btn-light me-5">{{'الغاء'}}</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit"  class="btn btn-primary" id="kt_add_word_submit">
                            <span class="indicator-label">{{'انشاء'}}</span>
                            <span class="indicator-progress">{{'الرجاء الانتظار'}}...
													<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div><!--end::Word options-->
                    <!--end::Main column-->
                </div>
                <!--end::Main column-->
            </form>
        </div>

        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
    <script src="{{asset('assets/js/cms/games/words/save-word.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/ar.min.js"></script>

    <script>
        const categories = {
            get: "{{ route('getCategories') }}",
        };
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wordContainer = document.getElementById('word-container');
            const addWordButton = document.getElementById('add-word');

            addWordButton.addEventListener('click', function () {
                const newWordRow = document.createElement('div');
                newWordRow.classList.add('form-group', 'row', 'word-row');

                newWordRow.innerHTML = `
                <div class="col-md-10">
                    <label class="form-label">{{'كلمة'}}:</label>
                    <input type="text" name="words[]" class="form-control mb-2 mb-md-0" placeholder="{{'أدخل كلمة'}}"/>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-light-danger mt-3 mt-md-8 remove-word">
                        <i class="ki-duotone ki-trash fs-5"></i>{{'حذف'}}
                </button>
            </div>
`;

                wordContainer.appendChild(newWordRow);

                // Attach event listener to the new remove button
                newWordRow.querySelector('.remove-word').addEventListener('click', function () {
                    newWordRow.remove();
                });
            });

            // Initial remove button event listeners
            wordContainer.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-word')) {
                    event.target.closest('.word-row').remove();
                }
            });
        });
    </script>

@endsection
