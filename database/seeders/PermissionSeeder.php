<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // ============================================================
            // PESSOAS
            // ============================================================
            ['name' => 'Visualizar Pessoas', 'slug' => 'people.view'],
            ['name' => 'Criar Pessoa',       'slug' => 'people.create'],
            ['name' => 'Editar Pessoa',      'slug' => 'people.update'],
            ['name' => 'Excluir Pessoa',     'slug' => 'people.delete'],

            // ============================================================
            // DEFICIÊNCIAS
            // ============================================================
            ['name' => 'Visualizar Deficiências', 'slug' => 'deficiency.view'],
            ['name' => 'Criar Deficiência',       'slug' => 'deficiency.create'],
            ['name' => 'Editar Deficiência',      'slug' => 'deficiency.update'],
            ['name' => 'Excluir Deficiência',     'slug' => 'deficiency.delete'],

            // ============================================================
            // CARGOS
            // ============================================================
            ['name' => 'Visualizar Cargos', 'slug' => 'position.view'],
            ['name' => 'Criar Cargo',       'slug' => 'position.create'],
            ['name' => 'Editar Cargo',      'slug' => 'position.update'],
            ['name' => 'Excluir Cargo',     'slug' => 'position.delete'],

            // ============================================================
            // ALUNOS
            // ============================================================
            ['name' => 'Visualizar Alunos', 'slug' => 'student.view'],
            ['name' => 'Criar Aluno',       'slug' => 'student.create'],
            ['name' => 'Editar Aluno',      'slug' => 'student.update'],
            ['name' => 'Excluir Aluno',     'slug' => 'student.delete'],

            // ============================================================
            // PROFISSIONAIS
            // ============================================================
            ['name' => 'Listar Profissionais', 'slug' => 'professional.index'],
            ['name' => 'Visualizar Profissional', 'slug' => 'professional.show'],
            ['name' => 'Criar Profissional', 'slug' => 'professional.create'],
            ['name' => 'Editar Profissional', 'slug' => 'professional.update'],
            ['name' => 'Excluir Profissional', 'slug' => 'professional.delete'],

            // ============================================================
            // RADAR INCLUSIVO — CATEGORIAS DE BARREIRAS (admin)
            // ============================================================
            ['name' => 'Gerenciar Categorias de Barreira', 'slug' => 'barrier-category.manage'],

            // ============================================================
            // RADAR INCLUSIVO — INSTITUIÇÕES (admin)
            // ============================================================
            ['name' => 'Gerenciar Instituições', 'slug' => 'institution.manage'],

            // ============================================================
            // RADAR INCLUSIVO — LOCALIZAÇÕES (admin)
            // ============================================================
            ['name' => 'Gerenciar Localizações', 'slug' => 'location.manage'],

            // ============================================================
            // RADAR INCLUSIVO — RECURSOS DE ACESSIBILIDADE (admin)
            // ============================================================
            ['name' => 'Gerenciar Recursos de Acessibilidade', 'slug' => 'accessibility-feature.manage'],

            // ============================================================
            // RADAR INCLUSIVO — TECNOLOGIAS ASSISTIVAS
            // ============================================================
            ['name' => 'Listar Tecnologias Assistivas',         'slug' => 'assistive-technology.index'],
            ['name' => 'Criar Tecnologia Assistiva (form)',      'slug' => 'assistive-technology.create'],
            ['name' => 'Salvar Tecnologia Assistiva',           'slug' => 'assistive-technology.store'],
            ['name' => 'Visualizar Tecnologia Assistiva',       'slug' => 'assistive-technology.show'],
            ['name' => 'Ver Inspeção de Tecnologia Assistiva',  'slug' => 'assistive-technology.inspection.show'],
            ['name' => 'Editar Tecnologia Assistiva (form)',    'slug' => 'assistive-technology.edit'],
            ['name' => 'Atualizar Tecnologia Assistiva',        'slug' => 'assistive-technology.update'],
            ['name' => 'Excluir Tecnologia Assistiva',          'slug' => 'assistive-technology.destroy'],
            ['name' => 'Gerar PDF de Tecnologia Assistiva',     'slug' => 'assistive-technology.pdf'],
            ['name' => 'Ver Logs de Tecnologia Assistiva',      'slug' => 'assistive-technology.logs'],

            // ============================================================
            // RADAR INCLUSIVO — BARREIRAS
            // ============================================================
            ['name' => 'Listar Barreiras',             'slug' => 'barrier.index'],
            ['name' => 'Criar Barreira (form)',         'slug' => 'barrier.create'],
            ['name' => 'Salvar Barreira',               'slug' => 'barrier.store'],
            ['name' => 'Visualizar Barreira',           'slug' => 'barrier.show'],
            ['name' => 'Ver Inspeção de Barreira',      'slug' => 'barrier.inspection.show'],
            ['name' => 'Editar Barreira (form)',        'slug' => 'barrier.edit'],
            ['name' => 'Atualizar Barreira',            'slug' => 'barrier.update'],
            ['name' => 'Excluir Barreira',              'slug' => 'barrier.destroy'],
            ['name' => 'Gerar PDF de Barreira',         'slug' => 'barrier.pdf'],

            // ============================================================
            // RADAR INCLUSIVO — MATERIAIS PEDAGÓGICOS ACESSÍVEIS
            // ============================================================
            ['name' => 'Listar Materiais Pedagógicos',            'slug' => 'material.index'],
            ['name' => 'Criar Material Pedagógico (form)',        'slug' => 'material.create'],
            ['name' => 'Salvar Material Pedagógico',              'slug' => 'material.store'],
            ['name' => 'Visualizar Material Pedagógico',          'slug' => 'material.show'],
            ['name' => 'Ver Inspeção de Material Pedagógico',     'slug' => 'material.inspection.show'],
            ['name' => 'Editar Material Pedagógico (form)',       'slug' => 'material.edit'],
            ['name' => 'Atualizar Material Pedagógico',           'slug' => 'material.update'],
            ['name' => 'Excluir Material Pedagógico',             'slug' => 'material.destroy'],
            ['name' => 'Gerar PDF de Material Pedagógico',        'slug' => 'material.pdf'],
            ['name' => 'Ver Logs de Material Pedagógico',         'slug' => 'material.logs'],

            // ============================================================
            // RADAR INCLUSIVO — AGENDA INSTITUCIONAL
            // ============================================================
            ['name' => 'Listar Eventos Institucionais',        'slug' => 'institutional-event.index'],
            ['name' => 'Criar Evento Institucional (form)',    'slug' => 'institutional-event.create'],
            ['name' => 'Salvar Evento Institucional',          'slug' => 'institutional-event.store'],
            ['name' => 'Visualizar Evento Institucional',      'slug' => 'institutional-event.show'],
            ['name' => 'Editar Evento Institucional (form)',   'slug' => 'institutional-event.edit'],
            ['name' => 'Atualizar Evento Institucional',       'slug' => 'institutional-event.update'],
            ['name' => 'Excluir Evento Institucional',         'slug' => 'institutional-event.destroy'],
            ['name' => 'Gerar PDF de Evento Institucional',    'slug' => 'institutional-event.pdf'],

            // ============================================================
            // RADAR INCLUSIVO — EMPRÉSTIMOS
            // ============================================================
            ['name' => 'Listar Empréstimos',         'slug' => 'loan.index'],
            ['name' => 'Criar Empréstimo (form)',     'slug' => 'loan.create'],
            ['name' => 'Salvar Empréstimo',           'slug' => 'loan.store'],
            ['name' => 'Visualizar Empréstimo',       'slug' => 'loan.show'],
            ['name' => 'Editar Empréstimo (form)',    'slug' => 'loan.edit'],
            ['name' => 'Atualizar Empréstimo',        'slug' => 'loan.update'],
            ['name' => 'Registrar Devolução',         'slug' => 'loan.return'],
            ['name' => 'Excluir Empréstimo',          'slug' => 'loan.destroy'],
            ['name' => 'Gerar PDF de Empréstimo',     'slug' => 'loan.pdf'],

            // ============================================================
            // RADAR INCLUSIVO — FILA DE ESPERA
            // ============================================================
            ['name' => 'Listar Fila de Espera',         'slug' => 'waitlist.index'],
            ['name' => 'Criar Entrada na Fila (form)',  'slug' => 'waitlist.create'],
            ['name' => 'Salvar Entrada na Fila',        'slug' => 'waitlist.store'],
            ['name' => 'Visualizar Entrada na Fila',    'slug' => 'waitlist.show'],
            ['name' => 'Editar Entrada na Fila (form)', 'slug' => 'waitlist.edit'],
            ['name' => 'Atualizar Entrada na Fila',     'slug' => 'waitlist.update'],
            ['name' => 'Cancelar Entrada na Fila',      'slug' => 'waitlist.cancel'],
            ['name' => 'Excluir Entrada na Fila',       'slug' => 'waitlist.destroy'],
            ['name' => 'Gerar PDF da Fila de Espera',   'slug' => 'waitlist.pdf'],

            // ============================================================
            // RELATÓRIOS
            // ============================================================
            ['name' => 'Acessar Relatórios',             'slug' => 'report.reports.index'],
            ['name' => 'Configurar Filtros do Relatório','slug' => 'report.configure'],
            ['name' => 'Executar Relatório',             'slug' => 'report.run'],
            ['name' => 'Exportar Relatório PDF',         'slug' => 'report.export.pdf'],

            ['name' => 'Visualizar Auditoria do Sistema', 'slug' => 'system.audit.view'],
            ['name' => 'Visualizar Administração do Sistema', 'slug' => 'system.admin.view'],
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['slug' => $p['slug']], ['name' => $p['name']]);
        }

        // Atribui todas as permissões ao Professor AEE
        $professorAee = Position::where('name', 'Professor AEE')->first();

        if ($professorAee) {
            $allPermissionIds = Permission::pluck('id')->toArray();

            if (method_exists($professorAee, 'permissions')) {
                $professorAee->permissions()->sync($allPermissionIds);
            } else {
                $pivotData = array_map(fn($id) => [
                    'position_id'   => $professorAee->id,
                    'permission_id' => $id,
                ], $allPermissionIds);

                DB::table('position_permission')->insertOrIgnore($pivotData);
            }
        }
    }
}
