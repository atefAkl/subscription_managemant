# Tooltips و Toggles - دليل الاستخدام

## التكوين ✅

تم تفعيل Tooltips و Toggles تلقائياً في `layouts/app.blade.php` مع:
- **Popper.js** لدعم الـ Tooltips والـ Popovers
- **Bootstrap JavaScript** للـ Toggles والـ Modals
- **jQuery** لوظائف مخصصة

---

## 1️⃣ استخدام Tooltips (تلميحات)

### الطريقة الأساسية:
```blade
<button class="btn btn-primary" 
        data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="نص التلميح">
    اضغط هنا
</button>
```

### الاتجاهات المتاحة:
- `data-bs-placement="top"` - أعلى العنصر
- `data-bs-placement="bottom"` - أسفل العنصر
- `data-bs-placement="left"` - يسار العنصر (يساراً في LTR)
- `data-bs-placement="right"` - يمين العنصر (يميناً في LTR)

### أمثلة:
```blade
<!-- Tooltip على زر -->
<button class="btn btn-info" 
        data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="هذا Tooltip في الأعلى">
    معلومة
</button>

<!-- Tooltip على رابط -->
<a href="#" 
   class="btn btn-warning" 
   data-bs-toggle="tooltip" 
   title="انقر للمزيد من المعلومات">
    <i class="fas fa-question-circle"></i> مساعدة
</a>

<!-- Tooltip على Badge -->
<span class="badge bg-success" 
      data-bs-toggle="tooltip" 
      title="هذا Badge نشط">
    نشط
</span>
```

---

## 2️⃣ استخدام Toggles (إظهار/إخفاء)

### الطريقة الأولى: باستخدام JavaScript Functions

#### Toggle (تبديل):
```blade
<button class="btn btn-primary" onclick="toggleElement('#myContent')">
    <i class="fas fa-toggle-on"></i> Toggle
</button>
<div id="myContent" style="display: none;">
    محتوى مخفي
</div>
```

#### Show (إظهار):
```blade
<button class="btn btn-success" onclick="showElement('#myContent')">
    <i class="fas fa-eye"></i> إظهار
</button>
<div id="myContent" class="show" style="display: none;">
    محتوى
</div>
```

#### Hide (إخفاء):
```blade
<button class="btn btn-danger" onclick="hideElement('#myContent')">
    <i class="fas fa-eye-slash"></i> إخفاء
</button>
<div id="myContent">
    محتوى مرئي
</div>
```

### الطريقة الثانية: باستخدام Bootstrap Collapse

```blade
<button class="btn btn-primary" 
        data-bs-toggle="collapse" 
        data-bs-target="#collapseExample">
    Toggle
</button>
<div class="collapse" id="collapseExample">
    محتوى يظهر/يختفي
</div>
```

### الطريقة الثالثة: jQuery Slide

```blade
<button class="btn btn-primary" 
        onclick="$('#myContent').slideToggle()">
    Slide Toggle
</button>
<div id="myContent">
    محتوى مع تأثير انزلاق
</div>
```

---

## 3️⃣ استخدام Popovers (نوافذ منبثقة)

```blade
<button class="btn btn-secondary" 
        data-bs-toggle="popover" 
        data-bs-placement="bottom" 
        data-bs-title="عنوان Popover" 
        data-bs-content="محتوى Popover">
    اضغط لعرض Popover
</button>
```

---

## 4️⃣ استخدام Accordions (عناصر قابلة للطي)

```blade
<div class="accordion" id="myAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#item1">
                العنوان الأول
            </button>
        </h2>
        <div id="item1" class="accordion-collapse collapse show" 
             data-bs-parent="#myAccordion">
            <div class="accordion-body">
                محتوى العنصر الأول
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#item2">
                العنوان الثاني
            </button>
        </h2>
        <div id="item2" class="accordion-collapse collapse" 
             data-bs-parent="#myAccordion">
            <div class="accordion-body">
                محتوى العنصر الثاني
            </div>
        </div>
    </div>
</div>
```

---

## 5️⃣ استخدام Modals (نوافذ منفصلة)

```blade
<!-- زر لفتح Modal -->
<button class="btn btn-danger" 
        data-bs-toggle="modal" 
        data-bs-target="#myModal">
    فتح Modal
</button>

<!-- Modal نفسه -->
<div class="modal fade" id="myModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">عنوان Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                محتوى Modal
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    إغلاق
                </button>
                <button type="button" class="btn btn-primary">
                    حفظ التغييرات
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## 6️⃣ استخدام Form Switches (مفاتيح التبديل)

```blade
<div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="mySwitch" 
           onchange="toggleElement('#myContent')">
    <label class="form-check-label" for="mySwitch">
        تفعيل الميزة
    </label>
</div>
<div id="myContent" style="display: none;">
    محتوى الميزة
</div>
```

---

## 7️⃣ أمثلة متقدمة

### مثال: عناصر التبديل المتعددة

```blade
<div class="btn-group mb-3" role="group">
    <button class="btn btn-outline-secondary" 
            onclick="showElement('#section1'); hideElement('#section2'); hideElement('#section3')">
        القسم الأول
    </button>
    <button class="btn btn-outline-secondary" 
            onclick="hideElement('#section1'); showElement('#section2'); hideElement('#section3')">
        القسم الثاني
    </button>
    <button class="btn btn-outline-secondary" 
            onclick="hideElement('#section1'); hideElement('#section2'); showElement('#section3')">
        القسم الثالث
    </button>
</div>

<div id="section1" class="p-3 bg-light rounded show">
    محتوى القسم الأول
</div>
<div id="section2" class="p-3 bg-light rounded" style="display: none;">
    محتوى القسم الثاني
</div>
<div id="section3" class="p-3 bg-light rounded" style="display: none;">
    محتوى القسم الثالث
</div>
```

### مثال: Toggle مع Animation

```blade
<button class="btn btn-warning" onclick="$('#animContent').slideToggle()">
    Toggle مع تأثير Slide
</button>
<div id="animContent" style="display: none;">
    محتوى مع تأثير انزلاق
</div>
```

### مثال: Tooltip + Toggle معاً

```blade
<button class="btn btn-info" 
        data-bs-toggle="tooltip" 
        title="اضغط لإظهار المزيد"
        onclick="toggleElement('#details')">
    <i class="fas fa-info-circle"></i> المزيد
</button>
<div id="details" style="display: none; margin-top: 1rem;">
    <div class="alert alert-info">
        <i class="fas fa-lightbulb me-2"></i>معلومات إضافية
    </div>
</div>
```

---

## 📝 الوظائف المتاحة

### JavaScript Functions:

```javascript
// Toggle (تبديل العنصر)
toggleElement('#elementId');

// Show (إظهار العنصر)
showElement('#elementId');

// Hide (إخفاء العنصر)
hideElement('#elementId');

// Slide Toggle (تبديل مع تأثير الانزلاق)
$('#elementId').slideToggle();

// Fade Toggle (تبديل مع تأثير التلاشي)
$('#elementId').fadeToggle();
```

---

## 🎨 ألوان Bootstrap

استخدم الألوان التالية مع Bootstrap:
- `bg-primary` - أزرق
- `bg-secondary` - رمادي
- `bg-success` - أخضر
- `bg-danger` - أحمر
- `bg-warning` - أصفر
- `bg-info` - أزرق فاتح
- `bg-light` - أبيض فاتح
- `bg-dark` - أسود

---

## ⚠️ ملاحظات مهمة

1. **يجب تفعيل Tooltips:** تتم تفعيلها تلقائياً عند تحميل الصفحة
2. **معرفات فريدة:** استخدم معرفات فريدة لكل عنصر `id="unique-name"`
3. **RTL Support:** جميع المكونات تدعم الاتجاه من اليمين لليسار
4. **Accessibility:** استخدم `aria-labels` و `role` للوصول الأفضل

---

## 🚀 البدء

لرؤية أمثلة عملية كاملة، قم بزيارة:
```
/examples/tooltips-toggles
```

هناك ستجد أمثلة تفاعلية جاهزة للاستخدام!
