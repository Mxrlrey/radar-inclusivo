<?php

namespace Tests\Unit;

use App\Enums\BarrierStatus;
use App\Enums\Gender;
use App\Enums\LoanStatus;
use App\Enums\Priority;
use App\Models\AccessibilityFeature;
use App\Models\AccessibleEducationalMaterial;
use App\Models\AssistiveTechnology;
use App\Models\AuditLog;
use App\Models\Backup;
use App\Models\Barrier;
use App\Models\BarrierCategory;
use App\Models\Deficiency;
use App\Models\Inspection;
use App\Models\InspectionImage;
use App\Models\Institution;
use App\Models\InstitutionalEvent;
use App\Models\Loan;
use App\Models\Location;
use App\Models\Permission;
use App\Models\Person;
use App\Models\Position;
use App\Models\Professional;
use App\Models\Student;
use App\Models\Traits\GlobalSearchable;
use App\Models\Traits\Auditable;
use App\Models\Traits\Reportable;
use App\Models\User;
use App\Models\Waitlist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_metadata_methods_are_exposed_by_models(): void
    {
        $models = [
            AccessibilityFeature::class,
            AccessibleEducationalMaterial::class,
            AssistiveTechnology::class,
            Barrier::class,
            BarrierCategory::class,
            Deficiency::class,
            Inspection::class,
            Institution::class,
            InstitutionalEvent::class,
            Loan::class,
            Location::class,
            Person::class,
            Position::class,
            Professional::class,
            Student::class,
            Waitlist::class,
        ];

        foreach ($models as $model) {
            $this->assertNotSame('', $model::getReportLabel());
            $this->assertNotEmpty($model::getReportColumns());
            $this->assertNotEmpty($model::getReportColumnLabels());
            $this->assertIsArray($model::getReportRelations());
            $this->assertIsArray($model::getEmbeddedRelations());
            $this->assertContains('deleted_at', $model::getBlacklist());
        }
    }

    public function test_relationship_methods_return_expected_relation_types(): void
    {
        Relation::morphMap(['institution' => Institution::class], merge: true);

        $this->assertInstanceOf(BelongsToMany::class, (new AccessibilityFeature())->materials());

        $material = new AccessibleEducationalMaterial();
        $this->assertInstanceOf(BelongsToMany::class, $material->deficiencies());
        $this->assertInstanceOf(BelongsToMany::class, $material->accessibilityFeatures());
        $this->assertInstanceOf(MorphMany::class, $material->inspections());
        $this->assertInstanceOf(MorphOne::class, $material->latestInspection());
        $this->assertInstanceOf(MorphMany::class, $material->loans());
        $this->assertInstanceOf(MorphMany::class, $material->waitlists());
        $this->assertInstanceOf(MorphMany::class, $material->logs());

        $technology = new AssistiveTechnology();
        $this->assertInstanceOf(BelongsToMany::class, $technology->deficiencies());
        $this->assertInstanceOf(MorphMany::class, $technology->inspections());
        $this->assertInstanceOf(MorphOne::class, $technology->latestInspection());
        $this->assertInstanceOf(MorphMany::class, $technology->loans());
        $this->assertInstanceOf(MorphMany::class, $technology->waitlists());
        $this->assertInstanceOf(MorphMany::class, $technology->logs());

        $auditLog = new AuditLog();
        $this->assertInstanceOf(BelongsTo::class, $auditLog->user());
        $this->assertInstanceOf(MorphTo::class, $auditLog->auditable());

        $this->assertInstanceOf(BelongsTo::class, (new Backup())->user());

        $barrier = new Barrier();
        $this->assertInstanceOf(BelongsTo::class, $barrier->registeredBy());
        $this->assertInstanceOf(BelongsTo::class, $barrier->institution());
        $this->assertInstanceOf(BelongsTo::class, $barrier->category());
        $this->assertInstanceOf(BelongsTo::class, $barrier->location());
        $this->assertInstanceOf(BelongsTo::class, $barrier->affectedStudent());
        $this->assertInstanceOf(BelongsTo::class, $barrier->affectedProfessional());
        $this->assertInstanceOf(BelongsToMany::class, $barrier->deficiencies());
        $this->assertInstanceOf(MorphMany::class, $barrier->inspections());
        $this->assertInstanceOf(MorphOne::class, $barrier->latestInspection());
        $this->assertInstanceOf(HasManyThrough::class, $barrier->allImages());

        $this->assertInstanceOf(HasMany::class, (new BarrierCategory())->barriers());

        $deficiency = new Deficiency();
        $this->assertInstanceOf(BelongsToMany::class, $deficiency->students());
        $this->assertInstanceOf(BelongsToMany::class, $deficiency->barriers());
        $this->assertInstanceOf(BelongsToMany::class, $deficiency->assistiveTechnologies());
        $this->assertInstanceOf(BelongsToMany::class, $deficiency->accessibleEducationalMaterials());

        $inspection = new Inspection();
        $this->assertInstanceOf(MorphTo::class, $inspection->inspectable());
        $this->assertInstanceOf(BelongsTo::class, $inspection->barrier());
        $this->assertInstanceOf(BelongsTo::class, $inspection->assistiveTechnology());
        $this->assertInstanceOf(BelongsTo::class, $inspection->accessibleEducationalMaterial());
        $this->assertInstanceOf(HasMany::class, $inspection->images());

        $this->assertInstanceOf(BelongsTo::class, (new InspectionImage())->inspection());

        $institution = new Institution();
        $this->assertInstanceOf(MorphOne::class, $institution->latestInspection());
        $this->assertInstanceOf(HasMany::class, $institution->locations());
        $this->assertInstanceOf(HasMany::class, $institution->barriers());

        $loan = new Loan();
        $this->assertInstanceOf(MorphTo::class, $loan->loanable());
        $this->assertInstanceOf(BelongsTo::class, $loan->assistiveTechnology());
        $this->assertInstanceOf(BelongsTo::class, $loan->accessibleEducationalMaterial());
        $this->assertInstanceOf(BelongsTo::class, $loan->student());
        $this->assertInstanceOf(BelongsTo::class, $loan->professional());
        $this->assertInstanceOf(BelongsTo::class, $loan->user());

        $this->assertInstanceOf(BelongsTo::class, (new Location())->institution());
        $this->assertInstanceOf(HasMany::class, (new Location())->barriers());
        $this->assertInstanceOf(BelongsToMany::class, (new Permission())->positions());
        $this->assertInstanceOf(HasOne::class, (new Person())->student());
        $this->assertInstanceOf(HasOne::class, (new Person())->professional());
        $this->assertInstanceOf(HasMany::class, (new Position())->professionals());
        $this->assertInstanceOf(BelongsToMany::class, (new Position())->permissions());

        $professional = new Professional();
        $this->assertInstanceOf(BelongsTo::class, $professional->person());
        $this->assertInstanceOf(BelongsTo::class, $professional->position());
        $this->assertInstanceOf(HasOne::class, $professional->user());
        $this->assertInstanceOf(HasMany::class, $professional->loans());
        $this->assertInstanceOf(HasMany::class, $professional->waitlists());
        $this->assertInstanceOf(HasMany::class, $professional->affectedBarriers());

        $student = new Student();
        $this->assertInstanceOf(BelongsTo::class, $student->person());
        $this->assertInstanceOf(HasMany::class, $student->loans());
        $this->assertInstanceOf(HasMany::class, $student->waitlists());
        $this->assertInstanceOf(HasMany::class, $student->affectedBarriers());

        $user = new User();
        $this->assertInstanceOf(BelongsTo::class, $user->professional());
        $this->assertInstanceOf(HasMany::class, $user->backups());

        $waitlist = new Waitlist();
        $this->assertInstanceOf(MorphTo::class, $waitlist->waitlistable());
        $this->assertInstanceOf(BelongsTo::class, $waitlist->assistiveTechnology());
        $this->assertInstanceOf(BelongsTo::class, $waitlist->accessibleEducationalMaterial());
        $this->assertInstanceOf(BelongsTo::class, $waitlist->student());
        $this->assertInstanceOf(BelongsTo::class, $waitlist->professional());
        $this->assertInstanceOf(BelongsTo::class, $waitlist->user());
    }

    public function test_accessors_and_model_events_cover_remaining_model_logic(): void
    {
        Storage::fake('public');

        $person = Person::factory()->create([
            'document' => '12345678901',
            'gender' => Gender::MALE,
            'photo' => 'photos/person.jpg',
        ]);
        $this->assertStringEndsWith('/storage/photos/person.jpg', $person->photo_url);
        $this->assertSame('123.456.789-01', $person->document_formatted);
        $this->assertSame(Gender::MALE->label(), $person->gender_label);
        $this->assertNotEmpty(Person::genderOptions());

        $person->document = 'ABC';
        $person->photo = null;
        $this->assertNull($person->photo_url);
        $this->assertSame('ABC', $person->document_formatted);

        $barrier = Barrier::factory()->create(['is_anonymous' => true]);
        $this->assertSame('Contribuidor Anônimo', $barrier->reporter_display_name);

        $user = User::factory()->create(['name' => 'Fallback User', 'is_admin' => false]);
        $identified = Barrier::factory()->create([
            'registered_by_user_id' => $user->id,
            'is_anonymous' => false,
        ]);
        $this->assertSame('Fallback User', $identified->reporter_display_name);

        $unidentified = new Barrier(['is_anonymous' => false]);
        $this->assertSame('Usuário não identificado', $unidentified->reporter_display_name);

        $inspection = Inspection::factory()->forBarrier($barrier)->create([
            'status' => BarrierStatus::RESOLVED,
        ]);
        $this->assertSame($barrier->name, $inspection->inspectable_name);
        $this->assertSame(BarrierStatus::RESOLVED, $barrier->latestStatus());

        $emptyInspection = new Inspection();
        $this->assertNull($emptyInspection->inspectable_name);

        Storage::disk('public')->put('inspections/remove.jpg', 'image');
        $image = InspectionImage::factory()->create([
            'inspection_id' => $inspection->id,
            'path' => 'inspections/remove.jpg',
        ]);
        $image->delete();
        Storage::disk('public')->assertMissing('inspections/remove.jpg');

        $position = Position::factory()->create();
        $permission = Permission::create(['name' => 'Ver algo', 'slug' => 'thing.view']);
        $position->permissions()->attach($permission);
        $professional = Professional::factory()->create(['position_id' => $position->id]);
        $allowedUser = User::factory()->create([
            'professional_id' => $professional->id,
            'is_admin' => false,
        ]);
        $admin = User::factory()->create(['is_admin' => true]);

        $this->assertTrue($admin->hasPermission('anything'));
        $this->assertTrue($allowedUser->hasPermission('thing.view'));
        $this->assertFalse($allowedUser->hasPermission('missing.permission'));
        $this->assertFalse(User::factory()->create(['is_admin' => false])->hasPermission('thing.view'));
        $this->assertSame($professional->person->name, $allowedUser->name);
        $this->assertStringEndsWith('/images/default-user.jpg', $allowedUser->photo_url);
        $this->withSession(['impersonator_id' => $admin->id]);
        $this->assertTrue($allowedUser->isImpersonating());
        $this->assertSame((bool) $admin->is_admin, $admin->isAdmin());

        $material = AccessibleEducationalMaterial::factory()->create();
        AuditLog::create([
            'action' => 'updated',
            'auditable_type' => $material->getMorphClass(),
            'auditable_id' => $material->id,
        ]);
        $this->assertGreaterThanOrEqual(1, AuditLog::query()->forModel($material)->count());
    }

    public function test_common_model_scopes_filter_and_keep_unfiltered_queries(): void
    {
        AccessibilityFeature::factory()->create(['name' => 'Libras', 'is_active' => true]);
        AccessibilityFeature::factory()->create(['name' => 'Braille', 'is_active' => false]);
        $this->assertSame(1, AccessibilityFeature::query()->filterName('Lib')->count());
        $this->assertSame(1, AccessibilityFeature::query()->filterStatus('0')->count());
        $this->assertSame(2, AccessibilityFeature::query()->filterName(null)->filterStatus('')->count());

        AccessibleEducationalMaterial::factory()->create([
            'name' => 'Mapa digital',
            'is_digital' => true,
            'quantity_available' => null,
            'is_active' => true,
        ]);
        AccessibleEducationalMaterial::factory()->create([
            'name' => 'Livro fisico',
            'is_digital' => false,
            'quantity_available' => 0,
            'is_active' => false,
        ]);
        $this->assertSame(1, AccessibleEducationalMaterial::query()->filterName('Mapa')->count());
        $this->assertSame(1, AccessibleEducationalMaterial::query()->active('0')->count());
        $this->assertSame(1, AccessibleEducationalMaterial::query()->available('1')->count());
        $this->assertSame(1, AccessibleEducationalMaterial::query()->available('0')->count());
        $this->assertSame(1, AccessibleEducationalMaterial::query()->digital('1')->count());
        $this->assertSame(2, AccessibleEducationalMaterial::query()->active('')->available(null)->digital(null)->count());

        AssistiveTechnology::factory()->create([
            'name' => 'Leitor digital',
            'is_digital' => true,
            'quantity_available' => null,
            'is_active' => true,
        ]);
        AssistiveTechnology::factory()->create([
            'name' => 'Reglete',
            'is_digital' => false,
            'quantity_available' => 0,
            'is_active' => false,
        ]);
        $this->assertSame(1, AssistiveTechnology::query()->filterName('Leitor')->count());
        $this->assertSame(1, AssistiveTechnology::query()->active('0')->count());
        $this->assertSame(1, AssistiveTechnology::query()->available('1')->count());
        $this->assertSame(1, AssistiveTechnology::query()->available('0')->count());
        $this->assertSame(1, AssistiveTechnology::query()->digital('1')->count());
        $this->assertSame(2, AssistiveTechnology::query()->active('')->available(null)->digital(null)->count());

        Backup::create(['file_name' => 'daily.zip', 'file_path' => 'daily.zip', 'size' => '1 KB', 'status' => 'success']);
        $backupUser = User::factory()->create();
        Backup::create(['file_name' => 'manual.zip', 'file_path' => 'manual.zip', 'size' => '1 KB', 'status' => 'failed', 'user_id' => $backupUser->id]);
        $this->assertSame(1, Backup::query()->filterName('daily')->count());
        $this->assertSame(1, Backup::query()->byType('failed')->count());
        $this->assertSame(1, Backup::query()->byUser($backupUser->id)->count());
        $this->assertSame(2, Backup::query()->filterName(null)->byType(null)->byUser(null)->count());

        $category = BarrierCategory::factory()->create(['name' => 'Arquitetonica', 'is_active' => true]);
        BarrierCategory::factory()->create(['name' => 'Comunicacional', 'is_active' => false]);
        $this->assertSame(1, BarrierCategory::query()->filterName('Arq')->count());
        $this->assertSame(1, BarrierCategory::query()->filterActive('0')->count());
        $this->assertSame(2, BarrierCategory::query()->filterName(null)->filterActive('')->count());

        Deficiency::factory()->create(['name' => 'Visual', 'cid_code' => 'H54', 'is_active' => true]);
        Deficiency::factory()->create(['name' => 'Auditiva', 'cid_code' => 'H90', 'is_active' => false]);
        $this->assertSame(1, Deficiency::query()->name('Vis')->count());
        $this->assertSame(1, Deficiency::query()->cid('H90')->count());
        $this->assertSame(1, Deficiency::query()->active(0)->count());
        $this->assertSame(2, Deficiency::query()->name(null)->cid(null)->active('')->count());

        $institution = Institution::factory()->create(['name' => 'Campus Bahia', 'city' => 'Salvador', 'state' => 'BA', 'is_active' => true]);
        Institution::factory()->create(['name' => 'Campus Sul', 'city' => 'Ilheus', 'state' => 'BA', 'is_active' => false]);
        $this->assertSame(1, Institution::query()->filterName('Bahia')->count());
        $this->assertSame(1, Institution::query()->filterStatus('0')->count());
        $this->assertSame(2, Institution::query()->filterLocation('BA')->count());
        $this->assertSame(2, Institution::query()->filterName(null)->filterStatus('')->filterLocation(null)->count());

        Location::factory()->create(['institution_id' => $institution->id, 'name' => 'Biblioteca', 'is_active' => true]);
        Location::factory()->create(['institution_id' => $institution->id, 'name' => 'Laboratorio', 'is_active' => false]);
        $this->assertSame(1, Location::query()->filterName('Bib')->count());
        $this->assertSame(2, Location::query()->filterInstitution('Campus')->count());
        $this->assertSame(1, Location::query()->filterActive('0')->count());
        $this->assertSame(2, Location::query()->filterName(null)->filterInstitution(null)->filterActive('')->count());

        Position::factory()->create(['name' => 'Professor', 'description' => 'Docente', 'is_active' => true]);
        Position::factory()->create(['name' => 'Tecnico', 'description' => 'Administrativo', 'is_active' => false]);
        $this->assertSame(1, Position::query()->name('Prof')->count());
        $this->assertSame(1, Position::query()->description('Admin')->count());
        $this->assertSame(1, Position::query()->active('0')->count());
        $this->assertSame(2, Position::query()->name(null)->description(null)->active('')->count());

        InstitutionalEvent::factory()->create(['title' => 'Semana inclusiva', 'is_active' => true]);
        InstitutionalEvent::factory()->create(['title' => 'Evento arquivado', 'is_active' => false]);
        $this->assertSame(1, InstitutionalEvent::query()->searchTitle('Semana')->count());
        $this->assertSame(1, InstitutionalEvent::query()->active(false)->count());
        $this->assertSame(2, InstitutionalEvent::query()->searchTitle(null)->count());
    }

    public function test_person_student_professional_barrier_loan_and_waitlist_scopes(): void
    {
        $student = Student::factory()->create(['registration' => 'MAT-001', 'is_active' => true]);
        $student->person->update(['name' => 'ZzModelsStudent Pessoa', 'email' => 'ana-models-unique@example.test', 'document' => '12345678901']);
        Student::factory()->create(['registration' => 'MAT-002', 'is_active' => false]);

        $position = Position::factory()->create();
        $professional = Professional::factory()->create(['position_id' => $position->id, 'registration' => 'PRO-001', 'is_active' => true]);
        $professional->person->update(['name' => 'ZzModelsProfessional Pessoa', 'email' => 'bruno-models-unique@example.test']);
        Professional::factory()->create(['is_active' => false]);

        $category = BarrierCategory::factory()->create(['name' => 'Fisica']);
        $barrier = Barrier::factory()->create([
            'name' => 'Degrau alto',
            'barrier_category_id' => $category->id,
            'affected_student_id' => $student->id,
            'affected_professional_id' => $professional->id,
            'priority' => Priority::HIGH,
        ]);
        Inspection::factory()->forBarrier($barrier)->create(['status' => BarrierStatus::IDENTIFIED]);

        $item = AssistiveTechnology::factory()->physical()->available()->loanable()->create(['name' => 'Scanner']);
        $material = AccessibleEducationalMaterial::factory()->physical()->available()->loanable()->create(['name' => 'Mapa tatil']);
        Loan::factory()->create([
            'loanable_id' => $item->id,
            'loanable_type' => $item->getMorphClass(),
            'student_id' => $student->id,
            'professional_id' => null,
            'user_id' => $loanUser = User::factory()->create()->id,
            'status' => LoanStatus::ACTIVE,
            'loan_date' => now()->subDays(3),
            'due_date' => now()->subDay(),
        ]);
        Loan::factory()->create([
            'loanable_id' => $material->id,
            'loanable_type' => $material->getMorphClass(),
            'student_id' => null,
            'professional_id' => $professional->id,
            'user_id' => User::factory()->create()->id,
            'status' => LoanStatus::RETURNED,
            'loan_date' => now(),
            'due_date' => now()->addDay(),
            'return_date' => now(),
        ]);
        Waitlist::factory()->create([
            'waitlistable_id' => $item->id,
            'waitlistable_type' => $item->getMorphClass(),
            'student_id' => $student->id,
            'professional_id' => null,
            'user_id' => User::factory()->create()->id,
        ]);
        Waitlist::factory()->create([
            'waitlistable_id' => $material->id,
            'waitlistable_type' => $material->getMorphClass(),
            'student_id' => null,
            'professional_id' => $professional->id,
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertSame(1, Person::query()->name('ZzModelsStudent')->count());
        $this->assertSame(1, Person::query()->document('123.456.789-01')->count());
        $this->assertSame(1, Person::query()->email('ana-models-unique@')->count());
        $this->assertGreaterThanOrEqual(2, Person::query()->name(null)->document(null)->email(null)->count());

        $this->assertSame(1, Student::query()->name('ZzModelsStudent')->count());
        $this->assertSame(1, Student::query()->registration('001')->count());
        $this->assertSame(1, Student::query()->active('0')->count());
        $this->assertSame(1, Student::query()->email('ana-models-unique@')->count());
        $this->assertSame(2, Student::query()->name(null)->registration(null)->active('')->email(null)->count());

        $this->assertSame(1, Professional::query()->name('ZzModelsProfessional')->count());
        $this->assertSame(1, Professional::query()->email('bruno-models-unique@')->count());
        $this->assertSame(1, Professional::query()->position($position->id)->count());
        $this->assertSame(1, Professional::query()->active('0')->count());
        $this->assertSame(2, Professional::query()->name(null)->email(null)->position('')->active('')->count());

        $this->assertSame(1, Barrier::query()->name('Degrau')->count());
        $this->assertSame(1, Barrier::query()->category('Fisica')->count());
        $this->assertSame(1, Barrier::query()->priority(Priority::HIGH->value)->count());
        $this->assertSame(1, Barrier::query()->status(BarrierStatus::IDENTIFIED->value)->count());
        $this->assertGreaterThanOrEqual(1, Barrier::query()->name(null)->category(null)->priority(null)->status(null)->count());

        $this->assertSame(1, Loan::query()->byStatus(LoanStatus::ACTIVE)->count());
        $this->assertSame(1, Loan::query()->student('ZzModelsStudent')->count());
        $this->assertSame(1, Loan::query()->professional('ZzModelsProfessional')->count());
        $this->assertSame(1, Loan::query()->item('scanner')->count());
        $this->assertSame(1, Loan::query()->item('mapa')->count());
        $this->assertSame(2, Loan::query()->loanedBetween(now()->subWeek()->toDateString(), now()->addDay()->toDateString())->count());
        $this->assertSame(1, Loan::query()->byUser($loanUser)->count());
        $this->assertSame(1, Loan::query()->active()->count());
        $this->assertSame(1, Loan::query()->overdue()->count());
        $this->assertSame(1, Loan::query()->returned()->count());
        $this->assertSame(2, Loan::query()->byStatus(null)->student(null)->professional(null)->item(null)->byUser(null)->loanedBetween(null, null)->count());

        $this->assertSame(1, Waitlist::query()->item('scanner')->count());
        $this->assertSame(1, Waitlist::query()->item('mapa')->count());
        $this->assertSame(1, Waitlist::query()->student('ZzModelsStudent')->count());
        $this->assertSame(1, Waitlist::query()->professional('ZzModelsProfessional')->count());
        $this->assertSame(2, Waitlist::query()->item(null)->student(null)->professional(null)->count());
    }

    public function test_reportable_and_global_search_trait_fallbacks(): void
    {
        Schema::create('model_search_related', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('model_search_fixtures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('related_id')->nullable();
            $table->string('name');
            $table->string('status')->nullable();
            $table->string('secret')->nullable();
        });

        ModelSearchRelated::query()->create(['id' => 1, 'name' => 'Relacionado']);
        ModelSearchFixture::query()->create([
            'related_id' => 1,
            'name' => 'Ativo Especial',
            'status' => 'ativo',
            'secret' => 'hidden',
        ]);

        $this->assertSame('Model Search Fixture', ModelSearchFixture::getReportLabel());
        $this->assertNull(ModelSearchFixture::getReportColumns());
        $this->assertSame([], ModelSearchFixture::getReportColumnLabels());
        $this->assertSame([], ModelSearchFixture::getEmbeddedRelations());
        $this->assertSame([], ModelSearchFixture::getReportRelations());
        $this->assertSame(['password', 'remember_token', 'deleted_at'], ModelSearchFixture::getBlacklist());

        $columns = ModelSearchFixture::getTranslatedColumns();
        $this->assertTrue($columns->has('name'));
        $this->assertTrue($columns->has('status'));

        $this->assertSame(1, ModelSearchFixture::query()->globalSearch(null)->count());
        $this->assertSame(1, ModelSearchFixture::query()->globalSearch('ativo')->count());
        $this->assertSame(1, ModelSearchFixture::query()->globalSearch('rel')->count());
        $this->assertSame(1, ModelSearchWithoutSearchable::query()->globalSearch('ativo')->count());

        Schema::dropIfExists('model_search_fixtures');
        Schema::dropIfExists('model_search_related');
    }

    public function test_auditable_logs_failures_and_reportable_returns_translated_label(): void
    {
        app('translator')->addLines([
            'database.models.TranslatedReportableFixture' => 'Modelo Traduzido',
        ], app()->getLocale());

        $this->assertSame('Modelo Traduzido', TranslatedReportableFixture::getReportLabel());

        Schema::drop('audit_logs');

        $model = new AccessibleEducationalMaterial(['name' => 'Sem tabela de auditoria']);
        $model->id = 123;

        AuditableFailureProbe::writeFailure('updated', $model, ['name' => 'old'], ['name' => 'new']);

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('action');
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        $this->assertTrue(true);
    }
}

class ModelSearchRelated extends Model
{
    protected $table = 'model_search_related';
    public $timestamps = false;
    protected $guarded = [];
}

class ModelSearchFixture extends Model
{
    use Reportable, GlobalSearchable;

    protected $table = 'model_search_fixtures';
    public $timestamps = false;
    protected $guarded = [];
    protected array $searchable = ['name', 'related.name'];
    protected array $searchAliases = ['ativo' => ['especial']];

    public function related(): BelongsTo
    {
        return $this->belongsTo(ModelSearchRelated::class, 'related_id');
    }
}

class ModelSearchWithoutSearchable extends Model
{
    use GlobalSearchable;

    protected $table = 'model_search_fixtures';
    public $timestamps = false;
    protected $guarded = [];
}

class TranslatedReportableFixture
{
    use Reportable;
}

class AuditableFailureProbe
{
    use Auditable;

    public static function writeFailure(string $action, Model $model, array $old, array $new): void
    {
        self::writeAudit($action, $model, $old, $new);
    }
}
