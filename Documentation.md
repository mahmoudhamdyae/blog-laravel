# 📚 دليل المراجعة الشاملة — مشروع Blog بـ Laravel

> **الهدف:** مراجعة كل سطر كتبته في المشروع ده، زي ما Senior Developer بيراجع كود Junior ويشرحه له بالتفصيل.

---

# 🗂️ الفهرس

1. [البنية العامة للمشروع](#البنية-العامة-للمشروع)
2. [الـ Layout — القالب الأساسي](#الـ-layout--القالب-الأساسي)
3. [جدول المستخدمين — Users](#جدول-المستخدمين--users)
4. [الموديل User](#الموديل-user)
5. [فيتشر Posts CRUD](#فيتشر-posts-crud)
    - [الـ Migration الأولى — إنشاء جدول posts](#الـ-migration-الأولى--إنشاء-جدول-posts)
    - [الـ Migration الثانية — إضافة عمود xyz](#الـ-migration-الثانية--إضافة-عمود-xyz)
    - [الـ Migration الثالثة — إضافة user_id](#الـ-migration-الثالثة--إضافة-user_id)
    - [الموديل Post](#الموديل-post)
    - [الـ PostController](#الـ-postcontroller)
    - [الـ Routes — مسارات البوستات](#الـ-routes--مسارات-البوستات)
    - [الـ Views — صفحات البوستات](#الـ-views--صفحات-البوستات)
6. [الـ Validation — التحقق من البيانات](#الـ-validation--التحقق-من-البيانات)
7. [الـ Model Binding — ربط الموديل بالراوت](#الـ-model-binding--ربط-الموديل-بالراوت)
8. [العلاقات — Relationships](#العلاقات--relationships)
9. [ملخص الأوامر Artisan](#ملخص-أوامر-artisan)

---

# البنية العامة للمشروع

```
blog/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── PostController.php   ← كنترولر البوستات
│   │       └── TestController.php   ← كنترولر تجريبي
│   └── Models/
│       ├── Post.php                 ← موديل البوست
│       └── User.php                 ← موديل المستخدم
├── database/
│   └── migrations/                  ← كل الـ migrations
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php        ← القالب الأساسي
│       └── posts/
│           ├── index.blade.php      ← صفحة كل البوستات
│           ├── show.blade.php       ← صفحة بوست واحد
│           ├── create.blade.php     ← صفحة إنشاء بوست
│           └── edit.blade.php       ← صفحة تعديل بوست
└── routes/
    └── web.php                      ← كل الـ routes
```

> **ايه اللى بيحصل هنا؟** ده هو الهيكل الأساسي لمشروع Laravel، كل حاجة في مكانها الصح. الكود بيتقسم على: Models للبيانات، Controllers للمنطق، Views للعرض، Routes للمسارات، Migrations لقاعدة البيانات.

---

# الـ Layout — القالب الأساسي

## أمر الإنشاء

الـ layout مش محتاج أمر artisan، إنت بتنشئه يدوي في:

```
resources/views/layouts/app.blade.php
```

## الكود

```blade
{{-- resources/views/layouts/app.blade.php --}}

<!doctype html>                           {{-- نوع المستند HTML5 --}}
<html lang="en">
  <head>
    <meta charset="utf-8">              {{-- ترميز النصوص Unicode --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">  {{-- تجاوب مع الموبايل --}}
    <title>@yield('title')</title>      {{-- مكان عنوان الصفحة، كل view بيحطه --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/..."  {{-- Bootstrap CSS من CDN --}}
          rel="stylesheet" ...>
  </head>
  <body>

    {{-- Nav Bar --}}
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Blog Post</a>    {{-- اسم الموقع في الشريط العلوي --}}
        ...
        <ul class="navbar-nav ...">
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('posts.index') }}">All Posts</a>
            {{-- رابط ديناميكي مربوط باسم الـ route --}}
          </li>
        </ul>
      </div>
    </nav>

    <div class="container mt-4">
        @yield('content')    {{-- هنا بيتحط محتوى كل صفحة --}}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/.../bootstrap.bundle.min.js" ...></script>
    @yield('scripts')        {{-- مكان الـ JavaScript الخاص بكل صفحة --}}
  </body>
</html>
```

> **ايه اللى بيحصل هنا؟** الـ layout ده هو القالب الأم، كل صفحة تانية بتورثه بـ `@extends('layouts.app')`. فيه ثلاث `@yield`: واحد لعنوان التاب، واحد للمحتوى الرئيسي، وواحد للـ JavaScript. الـ Navbar متواجدة دايمًا في كل الصفحات عشان بتيجي من اللاي أوت.

### الـ Blade Directives المستخدمة:

| الـ Directive                | الوظيفة                            |
| ---------------------------- | ---------------------------------- |
| `@yield('title')`            | بيحجز مكان للعنوان، كل view بيملاه |
| `@yield('content')`          | بيحجز مكان للمحتوى الرئيسي         |
| `@yield('scripts')`          | بيحجز مكان لـ JS الخاص بكل صفحة    |
| `{{ route('posts.index') }}` | بيولد رابط URL من اسم الـ route    |

---

# جدول المستخدمين — Users

## أمر الإنشاء

```bash
# جدول الـ users بييجي جاهز مع Laravel تلقائيًا
# الميجريشن موجودة في:
# database/migrations/0001_01_01_000000_create_users_table.php
```

> **ملاحظة:** من اسم الملف هتلاحظ إن الـ timestamp بيبدأ بـ `0001_01_01` ده يعني إنه migration قديم جدًا (predefined) جاي مع Laravel نفسه.

## الـ Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;  // الـ base class لكل migration
use Illuminate\Database\Schema\Blueprint;       // بتستخدمها لتعريف أعمدة الجدول
use Illuminate\Support\Facades\Schema;          // الـ facade للتعامل مع قاعدة البيانات

return new class extends Migration
{
    public function up(): void
    {
        // ✅ إنشاء جدول المستخدمين الرئيسي
        Schema::create('users', function (Blueprint $table) {
            $table->id();                             // عمود id تلقائي auto-increment (bigint)
            $table->string('name');                   // اسم المستخدم — نوع varchar
            $table->string('email')->unique();        // الإيميل — لازم يكون unique في قاعدة البيانات
            $table->timestamp('email_verified_at')->nullable(); // تاريخ التحقق من الإيميل — ممكن يكون فاضي
            $table->string('password');               // الباسورد — بيتخزن مشفر (hashed)
            $table->rememberToken();                  // للـ remember me feature — بيولد عمود remember_token
            $table->timestamps();                     // بيولد created_at و updated_at تلقائيًا
        });

        // ✅ إنشاء جدول reset الباسورد
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();  // الإيميل كـ primary key هنا
            $table->string('token');             // التوكن المؤقت لإعادة تعيين الباسورد
            $table->timestamp('created_at')->nullable();  // وقت إنشاء التوكن
        });

        // ✅ إنشاء جدول الـ sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();            // معرف الـ session
            $table->foreignId('user_id')->nullable()->index(); // الـ user المرتبط بالـ session — ممكن يكون Guest
            $table->string('ip_address', 45)->nullable(); // الـ IP address (45 حرف لدعم IPv6)
            $table->text('user_agent')->nullable();     // بيانات المتصفح
            $table->longText('payload');                // بيانات الـ session المشفرة
            $table->integer('last_activity')->index();  // آخر نشاط للـ session بالـ timestamp
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');                  // حذف الجداول بالترتيب العكسي
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

> **ايه اللى بيحصل هنا؟** الـ migration ده بيعمل ٣ جداول دفعة واحدة: `users` لبيانات المستخدمين الأساسية، `password_reset_tokens` لإعادة تعيين الباسورد، `sessions` لحفظ جلسات المستخدمين في قاعدة البيانات. دي كلها جزء من نظام الـ Authentication في Laravel.

---

# الموديل User

```php
<?php

namespace App\Models;                                    // الـ namespace — مكان الملف في المشروع

// use Illuminate\Contracts\Auth\MustVerifyEmail;     // معلّق عشان مش محتاجين التحقق من الإيميل
use Illuminate\Database\Eloquent\Factories\HasFactory;  // للـ testing والـ seeding
use Illuminate\Foundation\Auth\User as Authenticatable;  // الـ base class للـ authentication
use Illuminate\Notifications\Notifiable;                 // للإشعارات

class User extends Authenticatable                       // بيورث من Authenticatable مش Model عادي
{
    use HasFactory, Notifiable;                          // traits — بتضيف functionality جاهزة للموديل

    // الأعمدة اللي مسموح بـ mass assignment عليها
    protected $fillable = [
        'name',       // الاسم
        'email',      // الإيميل
        'password',   // الباسورد
    ];

    // الأعمدة اللي بتتخبى لما بتحول الموديل لـ JSON (مثلاً في API)
    protected $hidden = [
        'password',         // الباسورد — مش هترسله في أي response
        'remember_token',   // التوكن السري
    ];

    // بيحدد casting للأعمدة — يعني بيتعامل معاهم بنوع معين
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // بيعاملها كـ Carbon object مش string
            'password' => 'hashed',             // بيشفر الباسورد تلقائيًا لما تعمل create أو update
        ];
    }
}
```

> **ايه اللى بيحصل هنا؟** الـ User model بيورث من `Authenticatable` مش من `Model` العادي — ده بيدله إن الـ User ممكن يعمل Login. الـ `$fillable` بيحدد الأعمدة المسموح لـ `User::create([...])` إنها تملاها. الـ `casts` بيضمن إن الباسورد بيتشفر تلقائيًا لما تضيفه أو تعدله.

---

# فيتشر Posts CRUD

---

## الـ Migration الأولى — إنشاء جدول posts

### أمر الإنشاء

```bash
php artisan make:model Post -mc
# م = make:model
# -m  = بيعمل migration مع الموديل
# -c  = بيعمل controller مع الموديل
# (ممكن كمان -r لـ resource controller)
```

### كود الـ Migration

```php
<?php
// الملف: database/migrations/2025_10_18_133241_create_posts_table.php
// الـ timestamp في اسم الملف بيحدد ترتيب التنفيذ

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {  // بنشئ جدول اسمه posts
            $table->id();               // عمود id تلقائي (unsignedBigInteger + primary key)
            $table->string('title');    // عنوان البوست — varchar(255) بالافتراضي
            $table->string('description'); // وصف البوست — varchar(255)
            $table->timestamps();       // بيضيف created_at و updated_at تلقائيًا
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');  // لو عملنا rollback، بيمحي الجدول
    }
};
```

> **ايه اللى بيحصل هنا؟** ده أول migration، بيعمل جدول `posts` بأبسط شكل ممكن: id، عنوان، وصف، وتواريخ. الـ `up()` بيتشغل لما تعمل `migrate`، والـ `down()` بيتشغل لما تعمل `migrate:rollback`.

---

## الـ Migration الثانية — إضافة عمود xyz

### أمر الإنشاء

```bash
# لإضافة عمود جديد لجدول موجود، بتعمل migration جديدة:
php artisan make:migration add_xyz_to_posts_table --table=posts
# --table=posts        ← بتقول للـ generator إنك بتعدل جدول posts
# اسم الملف بيبدأ بـ add_ دي convention متعارف عليها
```

### كود الـ Migration

```php
<?php
// الملف: database/migrations/2025_10_24_121436_add_xyz_to_posts_table.php
// الـ timestamp اللي بعد 18 أكتوبر — بيتنفذ بعد الأول

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {  // Schema::table مش Schema::create
            $table->string('xyz')->nullable();  // عمود تجريبي — nullable() يعني ممكن يكون فاضي
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('xyz');  // لو عملنا rollback، بيشيل العمود
        });
    }
};
```

> **ايه اللى بيحصل هنا؟** الفرق المهم هنا: `Schema::create` للإنشاء، `Schema::table` للتعديل. العمود `xyz` ده عمود تجريبي اتعمل عشان تتعلم ازاي تضيف عمود جديد وكيف الـ `$fillable` في الموديل بيتعامل معاه (بيتجاهله).

---

## الـ Migration الثالثة — إضافة user_id

### أمر الإنشاء

```bash
php artisan make:migration add_user_id_to_posts_table --table=posts
```

### كود الـ Migration

```php
<?php
// الملف: database/migrations/2025_11_07_121429_add_user_id_to_posts_table.php
// جاية بعد ميجريشن xyz

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')   // نوع البيانات unsignedBigInteger عشان يتطابق مع id في users
                  ->after('description')            // بيحط العمود بعد عمود description
                  ->nullable();                     // nullable عشان البوستات القديمة مالهاش user

            $table->foreign('user_id')              // بيعرّف foreign key على عمود user_id
                  ->references('id')               // بيشير لعمود id في الجدول التاني
                  ->on('users')                    // الجدول اللي بيشير ليه هو users
                  ->onDelete('cascade');            // لو الـ user اتمسح، كل بوستاته بتتمسح معه
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // ملاحظة: الـ down() هنا فاضي — ده نسيان أو تجاهل متعمد
        });
    }
};
```

> **ايه اللى بيحصل هنا؟** ده أهم migration في المشروع! بيربط جدول `posts` بجدول `users` عن طريق `user_id`. الـ `unsignedBigInteger` لازم تستخدمه مع الـ foreign keys عشان يتطابق مع النوع الافتراضي للـ id في Laravel. الـ `onDelete('cascade')` بيضمن إن البوستات بتتمسح تلقائيًا لما الـ user بتاعها يتمسح.

---

## الموديل Post

### كود الموديل

```php
<?php
// الملف: app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;  // الـ base class لكل الموديلز

class Post extends Model
{
    // الأعمدة المسموح بـ mass assignment عليها
    protected $fillable = ['title', 'description', 'user_id'];
    // 'xyz' مش موجود هنا عمدًا ← لو حد حاول يبعته، Laravel بيتجاهله
    // ده بيحمي من هجمات Mass Assignment

    // ✅ Relationship: البوست تبع User
    public function user() {
        return $this->belongsTo(User::class);
        // belongsTo = "أنا تبع" — البوست تبع User
        // Laravel بيدور تلقائيًا على عمود user_id في جدول posts
    }

    // ✅ Relationship تانية نفس الوظيفة لكن بـ explicit foreign key
    public function postCreator() {
        return $this->belongsTo(User::class, 'user_id');
        // نفس belongsTo() لكن بتوضح العمود صراحةً
        // استخدمتها في الـ show view عشان اجيب بيانات الـ creator
    }
}
```

> **ايه اللى بيحصل هنا؟** الـ `$fillable` هو حارس البوابة — بيمنع mass assignment attacks. يعني لو المستخدم بعت `xyz=something`، Laravel بيتجاهله عشان مش موجود في الـ fillable. العلاقتين `user()` و`postCreator()` بيرجعوا نفس النتيجة، التاني بس بيوضح الـ foreign key صراحةً عشان تعلم الـ explicit syntax.

---

## الـ PostController

### أمر الإنشاء

```bash
php artisan make:controller PostController
# أو مع الموديل من البداية:
php artisan make:model Post -mc
# -m = migration
# -c = controller
```

### Method 1: `index()` — عرض كل البوستات

```php
function index() {
    $postsFromDB = Post::all(); // بيجيب كل البوستات من قاعدة البيانات كـ Collection
    // Post::all() = SELECT * FROM posts
    // بيرجع Eloquent Collection مش array عادي

    return view('posts.index', ['posts' => $postsFromDB]);
    // بيفتح ملف resources/views/posts/index.blade.php
    // وبيبعتله المتغير $posts بقيمة $postsFromDB
}
```

> **ايه اللى بيحصل هنا؟** أبسط method في الكنترولر. بنجيب كل السجلات من قاعدة البيانات بـ `Post::all()`، وبنبعتها للـ view. الـ `view()` بيأخد اسم الملف (بالنقطة بدل `/`) وـ array من البيانات.

---

### Method 2: `show(Post $post)` — عرض بوست واحد (Route Model Binding)

```php
// قبل كده كان الكود كده:
// function show($postId) {
//     $singlePostFromDb = Post::find($postId);    // بيدور على post بالـ id
//     if (is_null($singlePostFromDb)) {           // لو مش موجود
//         return to_route('posts.index');          // بيرجع للقائمة
//     }
//     return view('posts.show', ['post' => $singlePostFromDb]);
// }

// النسخة الحديثة باستخدام Route Model Binding:
function show(Post $post) {
    // Laravel بيعمل كل ده تلقائيًا ✨:
    // 1. بياخد الـ {post} من الـ URL
    // 2. بيدور على Post::find({post})
    // 3. لو مش موجود، بيرجع 404 تلقائيًا
    // 4. بيحقن الـ $post جاهز في الـ method

    return view('posts.show', ['post' => $post]);  // بيبعت الـ post للـ view
}
```

> **ايه اللى بيحصل هنا؟** ده من أجمل features في Laravel — الـ Route Model Binding. لما بتحط `Post $post` كـ parameter بدل `$postId`، Laravel بيعرف تلقائيًا إنه يجيب البوست من قاعدة البيانات بالـ id الجاي من الـ URL. لو مش موجود، بيديك 404 تلقائيًا من غير ما تكتب كود.

---

### Method 3: `create()` — فتح فورم الإنشاء

```php
function create() {
    $users = User::all();  // بيجيب كل المستخدمين
    // عشان نعرضهم في قائمة منسدلة (select) في الفورم

    return view('posts.create', ['users' => $users]);
    // بيفتح فورم الإنشاء ويبعت قائمة المستخدمين عشان يختار منهم
}
```

> **ايه اللى بيحصل هنا؟** الـ `create()` method مش بتضيف في قاعدة البيانات — ده دوره بس إنه يعرض الفورم. الـ GET request ← بيعرض فورم. الـ POST request ← بيحفظ البيانات (ده وظيفة `store()`).

---

### Method 4: `store(Request $request)` — حفظ البوست الجديد

```php
function store(Request $request) {

    // ✅ الخطوة الأولى: Validation — التحقق من البيانات
    request()->validate([
        'title'        => 'required|max:255|min:3',  // مطلوب، مش أكتر من 255 حرف، مش أقل من 3
        'description'  => 'required',                // مطلوب فقط
        'post_creator' => 'required|exists:users,id', // مطلوب ولازم يكون موجود في جدول users
    ]);
    // لو الـ validation فشل، Laravel بيرجع تلقائيًا للصفحة السابقة مع رسائل الأخطاء

    // ✅ الخطوة الثانية: استخراج البيانات من الـ request
    $title        = request()->title;         // قيمة input اسمه title
    $desc         = request()->description;   // قيمة textarea اسمه description
    $post_creator = request()->post_creator;  // قيمة select اسمه post_creator

    // ✅ الخطوة الثالثة: الحفظ في قاعدة البيانات (الطريقة الثانية — Mass Assignment)
    Post::create([
        'title'       => $title,         // بيحفظ العنوان
        'description' => $desc,          // بيحفظ الوصف
        'user_id'     => $post_creator,  // بيحفظ id الـ user
        'xyz'         => 'some value'    // هيتتجاهل! مش في $fillable في الموديل
    ]);

    // ✅ الخطوة الرابعة: Redirect
    return redirect()->route('posts.index');  // بيوجه المستخدم لصفحة قائمة البوستات
}
```

**الطريقة الأولى للحفظ (الأطول):**

```php
// $post = new Post;           // بيعمل object جديد من الموديل
// $post->title = $title;      // بيحدد قيمة كل عمود يدويًا
// $post->description = $desc;
// $post->save();              // بيتنفذ INSERT INTO posts ...
```

> **ايه اللى بيحصل هنا؟** الـ `store()` هي أكتر method فيها منطق. أول حاجة بتعمل Validation — لو فشل Laravel بيرجع تلقائيًا. لو نجح، بتاخد البيانات وبتحفظها بـ `Post::create()`. الـ `xyz` اللي بعتته في الكود بيتجاهل تلقائيًا عشان مش في `$fillable` — ده من أهم مفاهيم الأمان في Laravel.

---

### Method 5: `edit(Post $post)` — فتح فورم التعديل

```php
// function edit($postId) {     // الطريقة القديمة
function edit(Post $post) {     // باستخدام Route Model Binding
    $users = User::all();       // بيجيب المستخدمين لقائمة الـ select
    return view('posts.edit', ['post' => $post, 'users' => $users]);
    // بيبعت للـ view كلًا من: البوست الحالي (لإظهار بياناته) + قائمة المستخدمين
}
```

> **ايه اللى بيحصل هنا؟** نفس فكرة الـ `create()` لكن هنا بنبعت البوست الحالي كمان عشان يظهر بياناته في الفورم ويقدر المستخدم يعدل عليها.

---

### Method 6: `update(Request $request, $postId)` — تعديل بوست

```php
function update(Request $request, $postId) {

    // ✅ نفس الـ Validation بتاع store
    request()->validate([
        'title'        => 'required|max:255|min:3',
        'description'  => 'required',
        'post_creator' => 'required|exists:users,id',
    ]);

    // بيجيب البوست من قاعدة البيانات أو بيرجع 404
    $singlePostFromDb = Post::findOrFail($postId);
    // findOrFail = زي find() لكن لو مش موجود بيديك 404

    // استخراج البيانات الجديدة من الـ request
    $title        = request()->title;
    $desc         = request()->description;
    $post_creator = request()->post_creator;

    // تحديث البيانات في قاعدة البيانات
    $singlePostFromDb->update([
        'title'       => $title,         // تحديث العنوان
        'description' => $desc,          // تحديث الوصف
        'user_id'     => $post_creator,  // تحديث كاتب البوست
    ]);
    // بيتنفذ: UPDATE posts SET title=... WHERE id=...

    return redirect()->route('posts.show', $postId);  // بيوجه لصفحة البوست المعدّل
}
```

> **ايه اللى بيحصل هنا؟** الـ `update()` Method بتاخد الـ id من الـ URL، بتجيب البوست بـ `findOrFail()` (لو مش موجود ← 404)، بتعدل البيانات، وبتوجه للصفحة التفصيلية. لاحظ إن method الـ `update()` هنا بتاخد `$postId` كـ string مش Route Model Binding زي الـ show. ده inconsistency صغيرة لكنه شغال.

---

### Method 7: `destroy($postId)` — حذف بوست

```php
function destroy($postId) {
    // الطريقة الأسرع (معلّقة):
    // Post::where('id', $postId)->delete(); // بيعمل DELETE من غير ما يجيب الـ object

    // الطريقة المستخدمة:
    $post = Post::findOrFail($postId);  // بيجيب البوست أو 404
    $post->delete();                    // بيحذفه من قاعدة البيانات

    return redirect()->route('posts.index');  // بيوجه لقائمة البوستات بعد الحذف
}
```

> **ايه اللى بيحصل هنا؟** الحذف في خطوتين: اجيب الـ post الأول، وبعدين احذفه. ممكن تعملهم في سطر واحد بـ `Post::where('id', $postId)->delete()` لكن الطريقة دي مش بتقدر تعمل الـ Eloquent events زي `deleting` و`deleted`. الـ `findOrFail` مهم عشان لو الـ id مش موجود يديك 404 مش error.

---

## الـ Routes — مسارات البوستات

```php
<?php
// الملف: routes/web.php

use App\Http\Controllers\TestController;   // import الـ TestController
use Illuminate\Support\Facades\Route;      // import الـ Route facade
use App\Http\Controllers\PostController;   // import الـ PostController

// ✅ الـ Route الرئيسية — الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');  // بيرجع welcome view مباشرة من غير controller
});

// ✅ Routes تجريبية (للتعلم)
Route::get('/test', [TestController::class, 'firstAction']); // بيستدعي method في كنترولر
Route::get('/hello', [TestController::class, 'greet']);

// ✅ Posts Routes

// 1. عرض كل البوستات
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');
// GET /posts → PostController@index
// name('posts.index') ← تقدر تستخدمه في Blade كـ route('posts.index')

// 2. فتح فورم الإنشاء (لازم قبل route الـ {post} عشان متفسرش كـ post id)
Route::get('/posts/create', [PostController::class, 'create'])
    ->name('posts.create');
// GET /posts/create → PostController@create

// 3. عرض بوست واحد (Route Model Binding)
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->name('posts.show');
// GET /posts/5 → PostController@show (بيبعت Post بـ id=5)

// 4. حفظ بوست جديد
Route::post('/posts', [PostController::class, 'store'])
    ->name('posts.store');
// POST /posts → PostController@store
// لاحظ نفس الـ URL بس HTTP method مختلف

// 5. فتح فورم التعديل
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
    ->name('posts.edit');
// GET /posts/5/edit → PostController@edit

// 6. تعديل بوست
Route::put('/posts/{post}', [PostController::class, 'update'])
    ->name('posts.update');
// PUT /posts/5 → PostController@update

// 7. حذف بوست
Route::delete('/posts/{post}', [PostController::class, 'destroy'])
    ->name('posts.destroy');
// DELETE /posts/5 → PostController@destroy
```

> **ايه اللى بيحصل هنا؟** الـ routes دي بتطبق ما يُعرف بـ RESTful conventions. كل الـ routes ليها اسم (`->name()`) عشان نستخدمه في الـ `route()` helper بدل ما نكتب الـ URL hardcoded. ترتيب الـ routes مهم — `/posts/create` لازم يكون قبل `/posts/{post}` عشان لو عكسناهم، Laravel هيفسر كلمة "create" كـ post id!

### جدول الـ Routes:

| HTTP Method | URL                  | الـ method | الاسم           | الوظيفة         |
| ----------- | -------------------- | ---------- | --------------- | --------------- |
| GET         | `/posts`             | `index`    | `posts.index`   | عرض كل البوستات |
| GET         | `/posts/create`      | `create`   | `posts.create`  | فورم إنشاء      |
| GET         | `/posts/{post}`      | `show`     | `posts.show`    | عرض بوست واحد   |
| POST        | `/posts`             | `store`    | `posts.store`   | حفظ بوست جديد   |
| GET         | `/posts/{post}/edit` | `edit`     | `posts.edit`    | فورم تعديل      |
| PUT         | `/posts/{post}`      | `update`   | `posts.update`  | تعديل بوست      |
| DELETE      | `/posts/{post}`      | `destroy`  | `posts.destroy` | حذف بوست        |

---

## الـ Views — صفحات البوستات

---

### 1. صفحة عرض كل البوستات: `posts/index.blade.php`

```blade
{{-- resources/views/posts/index.blade.php --}}

@extends('layouts.app')
{{-- بيورث من القالب الأساسي layouts/app.blade.php --}}

@section('title') Posts @endsection
{{-- بيملا مكان @yield('title') في الـ layout بكلمة Posts --}}

@section('content')
{{-- كل اللي جوه ده هيظهر في مكان @yield('content') في الـ layout --}}

    {{-- زرار إنشاء بوست جديد --}}
    <div class="mt-4 text-center">
        <a href="{{ route('posts.create') }}" class="btn btn-success">Create Post</a>
        {{-- route('posts.create') بيولد الـ URL تلقائيًا بدل /posts/create --}}
    </div>

    {{-- جدول عرض البوستات --}}
    <table class="table m-4">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Posted By</th>
          <th scope="col">Created At</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($posts as $post)
        {{-- بيلف على كل بوست في الـ $posts collection --}}
        <tr>
          <th scope="row">{{ $post->id }}</th>         {{-- id البوست --}}
          <td>{{ $post->title }}</td>                  {{-- عنوان البوست --}}
          <td>{{ $post->user ? $post->user->name : 'not_found'}}</td>
          {{-- لو البوست ليه user، بيعرض اسمه — لو لأ بيكتب 'not_found' --}}
          {{-- $post->user بيستدعي الـ relationship user() في الموديل --}}
          <td>{{ $post->created_at->addDays(35)->format('Y-m-d') }}</td>
          {{-- created_at بيبقى Carbon object — addDays(35) بيضيف 35 يوم (تجريبي) --}}
          {{-- format('Y-m-d') بيحول التاريخ لـ string --}}
          <td>
            <div>
                <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info">View</a>
                {{-- رابط لصفحة تفاصيل البوست — بيبعت id البوست --}}
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary">Edit</a>
                {{-- رابط لصفحة تعديل البوست --}}
                <form method="POST" action="{{ route('posts.destroy', $post->id) }}"
                      class="delete-form" style="display:inline;">
                    @csrf
                    {{-- بيضيف hidden input بتوكن الحماية من CSRF attacks --}}
                    @method('DELETE')
                    {{-- HTML مش بيدعم DELETE — ده بيضيف hidden input _method=DELETE --}}
                    {{-- Laravel بيقراه ويعرف إن الـ request ده DELETE --}}
                    <button type="button" class="btn btn-danger delete-btn"
                            data-id="{{ $post->id }}">Delete</button>
                    {{-- type="button" مش "submit" عشان مش بيعمل submit مباشرة --}}
                    {{-- بيظهر modal تأكيد الحذف الأول --}}
                </form>
            </div>
          </td>
        </tr>
        @endforeach
        {{-- نهاية الـ loop --}}
      </tbody>
    </table>

    {{-- Modal تأكيد الحذف --}}
    <div class="modal fade" id="deleteConfirmModal" ...>
      ...
      <div class="modal-body">
        Are you sure you want to delete this post?  {{-- رسالة التأكيد --}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        {{-- لما يضغط هنا، الـ JavaScript بيعمل submit للفورم --}}
      </div>
    </div>

@endsection

@section('scripts')
{{-- JavaScript خاص بالصفحة دي — بيتحط في @yield('scripts') في الـ layout --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let targetForm = null;  // متغير بيحفظ الفورم اللي هيتعمل submit

    // بتلف على كل أزرار الحذف
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            targetForm = this.closest('form');  // بيجيب الفورم اللي جوا الزرار
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();  // بيظهر modal التأكيد
        });
    });

    // لما يضغط "Delete" في الـ modal
    document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
        if (targetForm) targetForm.submit();  // بيعمل submit للفورم = بيعمل DELETE request
    });
});
</script>
@endsection
```

> **ايه اللى بيحصل هنا؟** الصفحة دي بتعرض كل البوستات في جدول. زرار Delete مش بيعمل submit مباشرة — بيعرض modal تأكيد الحذف الأول عشان يمنع الحذف العرضي. الـ `@csrf` و`@method('DELETE')` ضروريين لأي فورم بيعمل تعديل أو حذف في Laravel.

---

### 2. صفحة تفاصيل البوست: `posts/show.blade.php`

```blade
{{-- resources/views/posts/show.blade.php --}}

@extends('layouts.app')
@section('title') Post Details @endsection

@section('content')

    {{-- كارت بيانات البوست --}}
    <div class="card m-4">
      <div class="card-header">Post Info</div>
      <div class="card-body">
        <h5 class="card-title">Title: {{ $post->title }}</h5>          {{-- عنوان البوست --}}
        <p class="card-text">Description: {{ $post->description }}</p>  {{-- وصف البوست --}}
      </div>
    </div>

    {{-- كارت بيانات كاتب البوست --}}
    <div class="card m-4">
      <div class="card-header">Post Creator Info</div>
      <div class="card-body">
        <h5 class="card-title">
            Name: {{ $post->user ? $post->user->name : 'not_found' }}
            {{-- لو البوست ليه user بيعرض اسمه — لو لأ يكتب not_found --}}
        </h5>
        <p class="card-text">
            Email: {{ $post->user ? $post->user->email : 'not_found' }}
        </p>
        <p class="card-text">
            Created At: {{ $post->user ? $post->postCreator->created_at : 'not_found' }}.
            {{-- هنا استخدم postCreator() relationship بدل user() —نفس النتيجة --}}
        </p>
      </div>
    </div>

@endsection
```

> **ايه اللى بيحصل هنا؟** الصفحة دي بتعرض بيانات بوست واحد. بتوصل الـ $post من الكنترولر. `$post->user->name` بيستدعي الـ relationship ويجيب اسم الـ user. استخدام الـ ternary operator (`? :`) مهم هنا عشان لو الـ user_id = null مش هتوصل `->name` على null وتاخد error.

---

### 3. صفحة إنشاء بوست: `posts/create.blade.php`

```blade
{{-- resources/views/posts/create.blade.php --}}

@extends('layouts.app')
@section('title') Create @endsection

@section('content')

    {{-- عرض أخطاء الـ Validation --}}
    @if ($errors->any())
        {{-- $errors متغير global في Blade — Laravel بيملاه تلقائيًا لو في validation errors --}}
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                {{-- all() بترجع كل رسائل الأخطاء كـ array --}}
                    <li>{{ $error }}</li>  {{-- بيعرض كل رسالة خطأ --}}
                @endforeach
            </ul>
        </div>
    @endif

    {{-- فورم الإنشاء --}}
    <form method="POST" action="{{ route('posts.store') }}">
    {{-- method="POST" عشان لازم يبعت البيانات --}}
    {{-- action بيوجهه لـ route posts.store (يعني POST /posts) --}}

        @csrf
        {{-- ضروري في كل فورم POST — بيضيف hidden input بتوكن CSRF للحماية --}}

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control"
                   value="{{ old('title') }}">
            {{-- old('title') بيرجع القيمة اللي كان الـ user كاتبها لو الـ validation فشل --}}
            {{-- بيحافظ على بيانات المستخدم ومش بيمسحها --}}
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">
                {{ old('description') }}  {{-- نفس فكرة old() للـ textarea --}}
            </textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Creator</label>
            <select name="post_creator" class="form-control">
                @foreach($users as $user)
                {{-- بيلف على المستخدمين الجايين من الكنترولر --}}
                    <option value="{{$user->id}}">{{$user->name}}</option>
                    {{-- الـ value هي الـ id عشان الكنترولر يحفظها --}}
                    {{-- اللي بيظهر للمستخدم هو اسم الـ user --}}
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Submit</button>
    </form>

@endsection
```

> **ايه اللى بيحصل هنا؟** الفورم ده بيعمل POST لـ `/posts`. الـ `@csrf` ضروري جداً — من غيره Laravel بيرفض الـ request. الـ `old()` function مهمة جداً لـ UX — بتخلي البيانات تفضل موجودة في الفورم لو الـ validation فشل ومش بيرجع المستخدم لفورم فاضي. الـ `$errors` متغير global تلقائي في كل الـ views.

---

### 4. صفحة تعديل البوست: `posts/edit.blade.php`

```blade
{{-- resources/views/posts/edit.blade.php --}}

@extends('layouts.app')
@section('title') Edit @endsection

@section('content')

    {{-- عرض أخطاء الـ Validation (نفس create) --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('posts.update', $post['id']) }}">
    {{-- action بيوجهه لـ route posts.update مع id البوست --}}
    {{-- $post['id'] بتشتغل زي $post->id مع Eloquent models --}}

        @csrf     {{-- ضروري في كل فورم --}}
        @method('PUT')
        {{-- ده مهم جداً! HTML Forms مش بتدعم PUT --}}
        {{-- @method('PUT') بيضيف hidden input: <input type="hidden" name="_method" value="PUT"> --}}
        {{-- Laravel بيقراها ويعرف إن الـ request ده PUT --}}

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control"
                   value="{{ $post->title }}">
            {{-- بيملا الـ input بالبيانات الحالية للبوست --}}
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">
                {{ $post->description }}  {{-- بيملا الـ textarea بالبيانات الحالية --}}
            </textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Post Creator</label>
            <select name="post_creator" class="form-control">
                @foreach($users as $user)
                    <option
                    {{-- الطريقة القديمة (معلّقة):
                    @if($user->id == $post->user_id)
                        selected
                    @endIf --}}
                    @selected($user->id == $post->user_id)
                    {{-- @selected() directive جديدة في Laravel —
                         بتضيف الـ selected attribute لو الشرط صح
                         أنيق أكتر من الـ @if القديمة --}}
                    value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>

@endsection
```

> **ايه اللى بيحصل هنا؟** الفرق الأساسي بين Edit وCreate هو ثلاث حاجات: (١) الـ action بيروح لـ `posts.update` مع id البوست، (٢) `@method('PUT')` عشان نوع الـ request مش POST، (٣) الـ inputs مليانة بالبيانات الحالية بدل ما يكونوا فاضيين. الـ `@selected()` directive جميلة وأنيقة بدل كتابة `@if` كاملة.

---

# الـ Validation — التحقق من البيانات

بيتعمل في الكنترولر باستخدام `request()->validate([...])`:

```php
request()->validate([
    'title'        => 'required|max:255|min:3',
    //                  ↑           ↑        ↑
    //               مطلوب     أقصى حد    أدنى حد
    'description'  => 'required',
    'post_creator' => 'required|exists:users,id',
    //                                ↑       ↑
    //                           اسم الجدول  اسم العمود
    //                  بيتحقق إن القيمة موجودة في جدول users في عمود id
]);
```

**إيه اللي بيحصل لو الـ Validation فشل؟**

```
المستخدم → POST /posts
    ↓
Laravel بيشغّل الـ Validation
    ↓ (فشل)
بيرجع redirect للصفحة السابقة تلقائياً
بيحط الـ errors في الـ session
بيحط الـ old input في الـ session
    ↓
في الـ View:
$errors->any()    ← تشيك لو في أخطاء
$errors->all()    ← تجيب كل الأخطاء
old('title')      ← ترجع القيمة القديمة
```

---

# الـ Model Binding — ربط الموديل بالراوت

ده من أقوى features في Laravel:

```php
// بدل الطريقة اليدوية:
function show($postId) {
    $post = Post::find($postId);     // جيب الـ post
    if (!$post) abort(404);          // تعامل مع الـ not found
    return view('posts.show', compact('post'));
}

// باستخدام Route Model Binding:
function show(Post $post) {
    // Laravel بيعمل كل ده تلقائيًا!
    return view('posts.show', compact('post'));
}
```

**إزاي بيشتغل؟** Laravel بيشوف إن:
١. في الـ Route فيه `{post}`
٢. في الـ Method parameter فيه `Post $post`
٣. الاسمين متطابقين (`post`)
٤. `Post` ده Eloquent Model

فبيعمل `Post::findOrFail($post)` تلقائيًا.

---

# العلاقات — Relationships

## Post belongsTo User (كل بوست تبع مستخدم واحد)

```php
// في الموديل Post:
public function user() {
    return $this->belongsTo(User::class);
    // belongsTo: "أنا التِّشايل" — البوست بيشيل الـ user_id
    // بيدور تلقائيًا على عمود user_id في جدول posts
}
```

**الاستخدام:**

```php
$post->user          // بيجيب الـ User object
$post->user->name    // بيجيب اسم الـ user
$post->user->email   // بيجيب إيميل الـ user

// في Blade:
{{ $post->user ? $post->user->name : 'not_found' }}
// ternary عشان نتعامل مع حالة إن user_id = null
```

## نوع العلاقة

```
posts table          users table
-----------          -----------
id                   id
title                name
description          email
user_id ---------->  id (foreign key)
created_at           ...
updated_at
```

---

# ملخص أوامر Artisan

```bash
# إنشاء موديل مع migration وكنترولر
php artisan make:model Post -mc
# -m = migration
# -c = controller
# -r = resource controller (بيولد كل الـ methods)
# مجموع: -mcr تعمل الثلاثة مع بعض

# إنشاء migration منفصلة
php artisan make:migration create_posts_table
php artisan make:migration add_user_id_to_posts_table --table=posts

# إنشاء ميجريشن وتشغيلها
php artisan migrate

# التراجع عن آخر migration
php artisan migrate:rollback

# إعادة عمل كل الـ migrations من الأول
php artisan migrate:fresh

# عرض كل الـ routes
php artisan route:list

# تشغيل الـ development server
php artisan serve
```

---

# 🎯 خلاصة رحلة بناء المشروع

```
1. أنشأت المشروع بـ: composer create-project laravel/laravel blog
2. عملت migration لجدول posts (3 migrations متتالية)
3. أنشأت الـ Post Model مع $fillable والـ relationships
4. أنشأت الـ PostController مع كل الـ CRUD methods
5. عرّفت الـ Routes في web.php مع أسماء
6. أنشأت الـ Layout في layouts/app.blade.php
7. أنشأت 4 views: index, show, create, edit
8. أضفت Validation في store() وupdate()
9. استخدمت Route Model Binding في show() وedit()
10. أضفت Delete Confirmation Modal بـ JavaScript
```

---

> **💡 نصيحة:** راجع هذا الملف وأنت بتتخيل الـ HTTP request رحلته من لما المستخدم يضغط على رابط أو زرار، لحد ما يشوف الصفحة. الترتيب دايمًا: **Route → Controller → Model → View**.
