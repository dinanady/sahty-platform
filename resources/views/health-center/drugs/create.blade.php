@extends('layouts.health-center.master')

@section('title', 'إضافة دواء جديد - المركز الصحي')

@section('content')
    <div class="container-fluid py-4">
        <!-- عنوان الصفحة -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="text-primary">إضافة دواء جديد</h2>
                <p class="text-muted">إرسال طلب لإضافة دواء جديد للنظام</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('health-center.drugs.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-1"></i>العودة لقائمة الأدوية
                </a>
            </div>
        </div>

        <!-- تنبيه -->
        <div class="alert alert-info">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                <div>
                    <strong>ملاحظة:</strong> عند إضافة دواء جديد، سيتم إرساله للمراجعة الحكومية.
                    لن يكون متاحاً للمرضى حتى تتم الموافقة عليه من الجهات المختصة.
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- نموذج إضافة الدواء -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>بيانات الدواء الجديد</h5>
                    </div>
                    <div class="card-body">
                        <form id="newDrugForm">
                            <div class="row">
                                <!-- معلومات أساسية -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">اسم الدواء التجاري <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required
                                        placeholder="أدخل اسم الدواء التجاري">
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الاسم العلمي</label>
                                    <input type="text" class="form-control" name="scientific_name"
                                        placeholder="أدخل الاسم العلمي للدواء">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الشركة المصنعة</label>
                                    <input type="text" class="form-control" name="manufacturer"
                                        placeholder="اسم الشركة المصنعة">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الفئة</label>
                                    <select class="form-select" name="category">
                                        <option value="">-- اختر الفئة --</option>
                                        <option value="مضادات حيوية">مضادات حيوية</option>
                                        <option value="مسكنات">مسكنات</option>
                                        <option value="مضادات الالتهاب">مضادات الالتهاب</option>
                                        <option value="أدوية القلب">أدوية القلب</option>
                                        <option value="أدوية السكري">أدوية السكري</option>
                                        <option value="أدوية الضغط">أدوية الضغط</option>
                                        <option value="فيتامينات ومكملات">فيتامينات ومكملات</option>
                                        <option value="أخرى">أخرى</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الشكل الدوائي</label>
                                    <select class="form-select" name="dosage_form">
                                        <option value="">-- اختر الشكل الدوائي --</option>
                                        <option value="أقراص">أقراص</option>
                                        <option value="كبسولات">كبسولات</option>
                                        <option value="شراب">شراب</option>
                                        <option value="حقن">حقن</option>
                                        <option value="مرهم">مرهم</option>
                                        <option value="قطرة">قطرة</option>
                                        <option value="بخاخ">بخاخ</option>
                                        <option value="أخرى">أخرى</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">السعر (ج.م)</label>
                                    <input type="number" class="form-control" name="price" step="0.01" min="0"
                                        placeholder="0.00">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الكمية الأولية <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="initial_stock" min="0" required
                                        placeholder="عدد الوحدات المتاحة">
                                    <small class="text-muted">الكمية المتاحة حالياً في مركزك</small>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">وصف الدواء</label>
                                    <textarea class="form-control" name="description" rows="3"
                                        placeholder="وصف مختصر عن الدواء واستخداماته"></textarea>
                                    <small class="text-muted">حد أقصى 1000 حرف</small>
                                </div>

                                <!-- خيارات إضافية -->
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="insurance_covered"
                                            id="insuranceCovered">
                                        <label class="form-check-label" for="insuranceCovered">
                                            مغطى بالتأمين الصحي
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-1"></i>إعادة تعيين
                                </button>
                                @can('hc-submit-new-drug')
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i>إرسال للمراجعة
                                </button>
                                @endcan
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white text-center">
                        <h5 class="m-auto"><i class="fas fa-lightbulb me-2"></i>إرشادات</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>تأكد من صحة اسم الدواء</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>أدخل الاسم العلمي إن أمكن</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>حدد الفئة المناسبة</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>اكتب وصفاً واضحاً</li>
                            <li class="mb-0"><i class="fas fa-check text-success me-2"></i>راجع البيانات قبل الإرسال</li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class=" m-auto"><i class="fas fa-exclamation-triangle me-2"></i>تنبيه</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">بعد إرسال الطلب:</p>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-1"><i class="fas fa-clock text-warning me-2"></i>ستظهر في قائمة "الأدوية المعلقة"
                            </li>
                            <li class="mb-1"><i class="fas fa-eye-slash text-muted me-2"></i>لن تكون متاحة للمرضى</li>
                            <li class="mb-1"><i class="fas fa-user-tie text-info me-2"></i>ستخضع للمراجعة الحكومية</li>
                            <li class="mb-0"><i class="fas fa-bell text-primary me-2"></i>ستصلك إشعار بالقرار</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#newDrugForm').on('submit', function (e) {
                e.preventDefault();
                submitNewDrug();
            });

            // تحديد النص في textarea
            $('textarea[name="description"]').on('input', function () {
                const maxLength = 1000;
                const currentLength = $(this).val().length;

                if (currentLength > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength));
                }

                $(this).next('.text-muted').text(`حد أقصى 1000 حرف (${currentLength}/${maxLength})`);
            });
        });

        function submitNewDrug() {
            const form = document.getElementById('newDrugForm');
            const formData = new FormData(form);

            // إزالة رسائل الخطأ السابقة
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: '/health-center/drugs-submit-new',
                method: 'POST',
                data: {
                    name: formData.get('name'),
                    scientific_name: formData.get('scientific_name'),
                    description: formData.get('description'),
                    manufacturer: formData.get('manufacturer'),
                    price: formData.get('price'),
                    insurance_covered: formData.has('insurance_covered') ? 1 : 0,
                    category: formData.get('category'),
                    dosage_form: formData.get('dosage_form'),
                    initial_stock: formData.get('initial_stock'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم بنجاح',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '{{ route("health-center.drugs.pending") }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: response.message,
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        // أخطاء التحقق
                        const errors = xhr.responseJSON.errors;

                        Object.keys(errors).forEach(function (field) {
                            const input = $(`[name="${field}"]`);
                            input.addClass('is-invalid');
                            input.siblings('.invalid-feedback').text(errors[field][0]);
                        });

                        showToast('يرجى تصحيح الأخطاء المذكورة', 'error');
                    } else {
                        showToast('حدث خطأ أثناء إرسال الطلب', 'error');
                    }
                }
            });
        }

        function resetForm() {
            if (confirm('هل أنت متأكد من إعادة تعيين النموذج؟ سيتم فقدان جميع البيانات المدخلة.')) {
                document.getElementById('newDrugForm').reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                $('textarea[name="description"]').trigger('input');
            }
        }
    </script>
@endsection
