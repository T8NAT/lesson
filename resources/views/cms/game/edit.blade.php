@extends('cms.layout.master')
@section('toolbar-title','تعديل اللعبة')
@section('breadcrumb','كافة الالعاب')
@section('sub-breadcrumb',$game->name)
@section('content')
    <!--begin::Heading-->
    <div class="card-border mb-13 text-center">
        <!--begin::Title-->
        <h1 class="mb-3"> تعديل لعبة {{$game->name}}</h1>
        <!--end::Title-->
        <!--begin::Description-->
        <div class="text-gray-400 fw-bold fs-5">يمكنك تصفح قائمة الالعاب من
            <a href="{{route('games.index')}}" class="fw-bolder link-primary">هنا</a>.
        </div>
        <!--end::Description-->
    </div>
    <!--end::Heading-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <form id="kt_add_game_form" action="{{ route('games.update',$game->id) }}" enctype="multipart/form-data" method="POST" class="form d-flex flex-column flex-lg-row" data-kt-redirect="{{route('games.index')}}">
                @csrf
                <!--begin::Aside column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <!--begin::Thumbnail settings-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{'ايقونة اللعبة'}}</h2>
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
                                <div class="image-input-wrapper w-150px h-150px" style="background-image: url({{Storage::url($game->icon)}}"></div>
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
                                    <input type="file" name="icon" accept=".png, .jpg, .jpeg" />
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
                            <div class="text-muted fs-7">{{'قم بتعيين ايقونة للعبة. يتم قبول ملفات الصور *.png و*.jpg و*.jpeg فقط'}}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Card body-->
                        @error('icon')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <!--end::Thumbnail settings-->
                    <!--begin::main category-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{'قسم اللعبة'}}</h2>
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
                            <select class="form-select mb-2 select2-hidden-accessible" data-control="select2" multiple name="category_id[]" data-hide-search="true" aria-hidden="true"  data-placeholder="حدد خياراً">
                                <option></option>
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">{{'تعيين القسم للعبة.'}}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::main category-->
                    <!--begin::type-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{'نوع اللعبة'}}</h2>
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
                            <select class="form-select mb-2 select2-hidden-accessible" data-control="select2" name="type_id" data-hide-search="true" aria-hidden="true"  data-placeholder="حدد خياراً">
                                <option></option>
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">{{'تعيين نوع اللعبة.'}}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::type-->
                    <!--begin::Status-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>{{'الحالة'}}</h2>
                            </div>
                            <!--end::Card title-->
                            <!--begin::Card toolbar-->
                            <div class="card-toolbar">
                                <div class="rounded-circle bg-success w-15px h-15px" id="kt_add_game_status"></div>
                            </div>
                            <!--begin::Card toolbar-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Select2-->
                            <select class="form-select mb-2" name="status" data-control="select2" data-hide-search="true" data-placeholder="{{'حدد خياراً'}}" id="kt_add_game_status_select">
                                <option></option>
                                <option value="active">{{'فعالة'}}</option>
                                {{--                                    <option value="scheduled">Scheduled</option>--}}
                                <option value="inactive">{{'غير فعالة'}}</option>
                            </select>
                            <!--end::Select2-->
                            <!--begin::Description-->
                            <div class="text-muted fs-7">{{'تعيين حالة اللعبة.'}}</div>
                            <!--end::Description-->
                            @error('status')
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Status-->
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
                                <label class="required form-label">{{'اسم اللعبة'}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="name" id="name"  class="form-control mb-2" placeholder="{{'قم بادخال اسم اللعبة هنا'}}" value="{{$game->name}}" />
                                <!--end::Input-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">{{'يجب أن يكون اسم اللعبة فريدًا ومن المستحسن أن يكون فريدًا.'}}</div>
                                <!--end::Description-->
                                @error('name')
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
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{$game->description}}</textarea>
                                <!--end::Editor-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">{{'قم بتعيين وصف للعبة لتحسين الرؤية.'}}</div>
                                <!--end::Description-->
                                @error('description')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <!--end::Input group-->

                            <!--begin::Media-->
                            <div class="card card-flush py-4">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>الوسائط</h2>
                                    </div>
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0">
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-2">
                                        <!--begin::Dropzone-->
                                        <div class="dropzone" id="kt_add_game_media">
                                            <!--begin::Message-->
                                            <div class="dz-message needsclick">
                                                <!--begin::Icon-->
                                                <i class="ki-duotone ki-file-up text-primary fs-3x">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                </i>
                                                <!--end::Icon-->
                                                <!--begin::Info-->
                                                <div class="ms-4">
                                                    <h3 class="fs-5 fw-bold text-gray-900 mb-1">{{'قم باسقاط الملفات هنا او اضغط هنا لرفع الملفات'}}.</h3>
                                                    <span class="fs-7 fw-semibold text-gray-400">{{'تحميل مايصل الى 10 صور'}}</span>
                                                </div>
                                                <!--end::Info-->
                                            </div>
                                        </div>
                                        <!--end::Dropzone-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Description-->
                                    <div class="text-muted fs-7">{{'قم بتعيين معرض وسائط اللعبة'}}.</div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Card header-->
                            </div>
                            <!--end::Media-->
                        </div>
                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->
                    <!--begin::Meta options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{'خيارات ميتا'}}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <div class="mb-10">
                                <!--begin::Label-->
                                <label class="form-label">({{'slug'}}){{ 'عنوان الرابط' }}</label>
                                <!--end::Label-->
                                <!--begin::Input group with button-->
                                <div class="input-group mb-2">
                                    <input type="text" readonly id="slug" class="form-control" name="slug" placeholder="عنوان الرابط" />
                                </div>
                                <!--end::Input group with button-->
                                <!--begin::Description-->
                                <div class="text-muted fs-7">{{ 'توليد عنوان رابط تلقائي' }}</div>
                                <!--end::Description-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        @error('slug')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <!--end::Card header-->
                    </div>
                    <!--end::Meta options-->
                    <div class="d-flex justify-content-end">
                        <!--begin::Button-->
                        <a href="{{ route('games.index') }}" id="kt_add_game_cancel" class="btn btn-light me-5">{{'الغاء'}}</a>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit"  class="btn btn-primary" id="kt_add_game_submit">
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
    <script src="{{asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
    <script src="{{asset('assets/js/cms/games/save-game.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/ar.min.js"></script>

    <script>
        const routes = {
            post: "{{ route('store-media') }}",
        };
        const categories = {
            get: "{{ route('getCategories') }}",
        };
        const types = {
            get: "{{route('getTypes')}}"
        }
    </script>
    <script>
        document.getElementById('name').addEventListener('input', function() {
            let title = this.value;
            let slug = title
                .replace(/[^أ-ي0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with dashes
                .replace(/-+/g, '-'); // Replace multiple dashes with a single dash
            document.getElementById('slug').value = slug;
        });

    </script>

@endsection
