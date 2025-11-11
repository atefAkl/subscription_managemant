@extends('layouts.app')

@section('title', 'أمثلة Tooltips و Toggles')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">أمثلة Tooltips و Toggles</h1>

    <!-- Tooltips Examples -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle me-2"></i>أمثلة Tooltips
            </h5>
        </div>
        <div class="card-body">
            <p class="mb-3">
                <button class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="هذا Tooltip في الأعلى">
                    Tooltip في الأعلى
                </button>
            </p>

            <p class="mb-3">
                <button class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="right" title="هذا Tooltip على اليمين">
                    Tooltip على اليمين
                </button>
            </p>

            <p class="mb-3">
                <button class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="bottom" title="هذا Tooltip في الأسفل">
                    Tooltip في الأسفل
                </button>
            </p>

            <p class="mb-3">
                <button class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="left" title="هذا Tooltip على اليسار">
                    Tooltip على اليسار
                </button>
            </p>

            <p class="mb-3">
                <a href="#" class="btn btn-warning" data-bs-toggle="tooltip" title="يمكنك إضافة Tooltip على أي عنصر">
                    <i class="fas fa-question-circle me-2"></i>Tooltip على رابط
                </a>
            </p>

            <p class="mb-3">
                <span class="badge bg-success" data-bs-toggle="tooltip" title="هذا Badge له Tooltip">
                    <i class="fas fa-check me-1"></i>Badge مع Tooltip
                </span>
            </p>
        </div>
    </div>

    <!-- Toggle Examples -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-toggle-on me-2"></i>أمثلة Toggles
            </h5>
        </div>
        <div class="card-body">
            <!-- Toggle Button -->
            <div class="mb-4">
                <h6>Toggle عنصر بزر:</h6>
                <button class="btn btn-primary" onclick="toggleElement('#toggleContent1')">
                    <i class="fas fa-toggle-on me-2"></i>Toggle المحتوى
                </button>
                <div id="toggleContent1" class="mt-3 p-3 bg-light rounded" style="display: none;">
                    <p class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>هذا المحتوى مخفي ويظهر عند الضغط على الزر!
                    </p>
                </div>
            </div>

            <!-- Toggle Class -->
            <div class="mb-4">
                <h6>Toggle Class (إظهار/إخفاء):</h6>
                <button class="btn btn-success" onclick="toggleElement('#toggleContent2')">
                    <i class="fas fa-eye-slash me-2"></i>إظهار/إخفاء
                </button>
                <div id="toggleContent2" class="mt-3 p-3 bg-light rounded">
                    <p class="mb-0">
                        <i class="fas fa-star me-2"></i>هذا المحتوى يمكن إظهاره وإخفاؤه!
                    </p>
                </div>
            </div>

            <!-- Multiple Toggles -->
            <div class="mb-4">
                <h6>عدة Toggles:</h6>
                <div class="btn-group mb-3" role="group">
                    <button class="btn btn-outline-secondary" onclick="showElement('#section1'); hideElement('#section2'); hideElement('#section3')">
                        <i class="fas fa-home me-1"></i>القسم الأول
                    </button>
                    <button class="btn btn-outline-secondary" onclick="hideElement('#section1'); showElement('#section2'); hideElement('#section3')">
                        <i class="fas fa-cogs me-1"></i>القسم الثاني
                    </button>
                    <button class="btn btn-outline-secondary" onclick="hideElement('#section1'); hideElement('#section2'); showElement('#section3')">
                        <i class="fas fa-info-circle me-1"></i>القسم الثالث
                    </button>
                </div>

                <div id="section1" class="p-3 bg-info-light rounded show">
                    <h6>القسم الأول</h6>
                    <p class="mb-0">محتوى القسم الأول يظهر بشكل افتراضي.</p>
                </div>

                <div id="section2" class="p-3 bg-success-light rounded" style="display: none;">
                    <h6>القسم الثاني</h6>
                    <p class="mb-0">هذا محتوى القسم الثاني.</p>
                </div>

                <div id="section3" class="p-3 bg-warning-light rounded" style="display: none;">
                    <h6>القسم الثالث</h6>
                    <p class="mb-0">هذا محتوى القسم الثالث.</p>
                </div>
            </div>

            <!-- Toggle with Animation -->
            <div class="mb-4">
                <h6>Toggle مع Animation:</h6>
                <button class="btn btn-warning" onclick="$('#animContent').slideToggle()">
                    <i class="fas fa-bars me-2"></i>Toggle مع تأثير Slide
                </button>
                <div id="animContent" class="mt-3 p-3 bg-light rounded" style="display: none;">
                    <p class="mb-0">
                        <i class="fas fa-magic me-2"></i>هذا المحتوى يظهر/يختفي مع تأثير Slide سلس!
                    </p>
                </div>
            </div>

            <!-- Toggle Checkbox Style -->
            <div class="mb-4">
                <h6>Toggle Style Checkbox:</h6>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="toggleSwitch" onchange="toggleElement('#switchContent')">
                    <label class="form-check-label" for="toggleSwitch">
                        تفعيل المحتوى
                    </label>
                </div>
                <div id="switchContent" class="mt-3 p-3 bg-light rounded" style="display: none;">
                    <p class="mb-0">
                        <i class="fas fa-check-circle me-2"></i>المحتوى مفعل الآن!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Examples -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">
                <i class="fas fa-rocket me-2"></i>أمثلة متقدمة
            </h5>
        </div>
        <div class="card-body">
            <!-- Popover Example -->
            <div class="mb-4">
                <h6>Popover (نافذة منبثقة):</h6>
                <button class="btn btn-secondary" data-bs-toggle="popover" data-bs-placement="bottom" 
                        data-bs-title="عنوان Popover" 
                        data-bs-content="هذا محتوى الـ Popover الذي يظهر عند الضغط على الزر">
                    <i class="fas fa-comments me-2"></i>اضغط لعرض Popover
                </button>
            </div>

            <!-- Collapsible with Toggle -->
            <div class="mb-4">
                <h6>Collapsible Accordion:</h6>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                <i class="fas fa-chevron-left me-2"></i>العنصر الأول
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                محتوى العنصر الأول يظهر بشكل افتراضي.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                <i class="fas fa-chevron-left me-2"></i>العنصر الثاني
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                محتوى العنصر الثاني.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                                <i class="fas fa-chevron-left me-2"></i>العنصر الثالث
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                محتوى العنصر الثالث.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Toggle -->
            <div class="mb-4">
                <h6>Modal (نافذة منفصلة):</h6>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    <i class="fas fa-window-maximize me-2"></i>فتح Modal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Example -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-window-maximize me-2"></i>نافذة Modal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>
                    <i class="fas fa-info-circle me-2"></i>هذه نافذة Modal (نافذة منفصلة) يمكن استخدامها لعرض محتوى مهم.
                </p>
                <p>
                    يمكنك استخدام هذه النوافذ لتأكيد الإجراءات أو عرض معلومات إضافية.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button type="button" class="btn btn-danger">حفظ التغييرات</button>
            </div>
        </div>
    </div>
</div>

@endsection
