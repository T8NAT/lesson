@extends('cms.layout.master')
@section('title','إعدادات ')
@section('toolbar-title','الإعدادات')
@section('breadcrumb','')
@section('content')

    @if(session()->has('alert-type'))
        <div class="alert {{ session()->get('alert-type') }} alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Lesson</h3>
            @if(!$settings)
                <a href="{{ route('settings.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> ضبط الاعداد
                </a>
            @endif
            @if($settings)
                <a href="{{ route('settings.edit',$settings->id) }}" class="btn btn-success">تعديل الإعدادات</a>
            @endif
        </div>

        @if($settings)
            <div class="card-body">
                <div class="text-center mb-4">

                    <img src="{{ Storage::url($settings->logo)}}" class="image-input-wrapper w-200px  bgi-position-center mb-5"  alt="{{$settings->alt}}">
                </div>

                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>اسم التطبيق:</strong> {{ $settings->name }}</li>
                    <li class="list-group-item"><strong>البريد الإلكتروني:</strong> {{ $settings->email }}</li>
                    <li class="list-group-item"><strong>الهاتف:</strong> {{ $settings->phone }} <span class="badge bg-success">مُحقق</span></li>
                    <li class="list-group-item"><strong>رابط التطبيق:</strong> <a href="{{ $settings->url }}" target="_blank">{{ $settings->url }}</a></li>
                    <li class="list-group-item"><strong>التواصل الاجتماعي:</strong>
                        <a href="{{ $settings->linkedin }}" target="_blank"><i class="fab fa-linkedin fa-lg me-2"></i></a>
                        <a href="{{ $settings->facebook }}" target="_blank"><i class="fab fa-facebook fa-lg me-2"></i></a>
                        <a href="{{ $settings->instagram }}" target="_blank"><i class="fab fa-instagram fa-lg me-2"></i></a>
                        <a href="{{ $settings->x }}" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <button onclick="confirmDelete(this, '{{ $settings->id }}')" class="btn btn-danger">حذف الإعدادات</button>
            </div>
        @else
            <div class="card-body text-center text-muted">لم يتم ضبط إعدادات التطبيق بعد</div>
        @endif
    </div>
        </div>
        </div>
@endsection

@section('scripts')
    <script src="{{ asset('/assets/js/axios.js') }}"></script>
    <script>
        function confirmDelete(button, id) {
            Swal.fire({
                title: 'هل أنت متأكد من الحذف؟',
                text: "لا يمكنك التراجع بعد الحذف!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteSettings(button, id);
                }
            });
        }

        function deleteSettings(button, id) {
            axios.delete('/dashboard/settings/' + id)
                .then(response => {
                    Swal.fire('تم الحذف!', response.data.message, 'success');
                    location.reload();
                })
                .catch(error => {
                    Swal.fire('خطأ!', error.response.data.message, 'error');
                });
        }
    </script>
@endsection
