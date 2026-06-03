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
            // Atendimento Educacional Especializado
            ['name' => 'Listar alunos', 'slug' => 'student.view'],
            ['name' => 'Cadastrar alunos', 'slug' => 'student.create'],
            ['name' => 'Editar alunos', 'slug' => 'student.update'],
            ['name' => 'Excluir alunos', 'slug' => 'student.delete'],

            ['name' => 'Listar profissionais', 'slug' => 'professional.index'],
            ['name' => 'Visualizar profissionais', 'slug' => 'professional.show'],
            ['name' => 'Cadastrar profissionais', 'slug' => 'professional.create'],
            ['name' => 'Editar profissionais', 'slug' => 'professional.update'],
            ['name' => 'Excluir profissionais', 'slug' => 'professional.delete'],
            ['name' => 'Acessar menu de profissionais', 'slug' => 'professional.view'],

            ['name' => 'Listar deficiências', 'slug' => 'deficiency.view'],
            ['name' => 'Cadastrar deficiências', 'slug' => 'deficiency.create'],
            ['name' => 'Editar deficiências', 'slug' => 'deficiency.update'],
            ['name' => 'Excluir deficiências', 'slug' => 'deficiency.delete'],

            ['name' => 'Listar cargos e permissões', 'slug' => 'position.view'],
            ['name' => 'Cadastrar cargos e permissões', 'slug' => 'position.create'],
            ['name' => 'Editar cargos e permissões', 'slug' => 'position.update'],
            ['name' => 'Excluir cargos e permissões', 'slug' => 'position.delete'],

            // Radar Inclusivo
            ['name' => 'Listar tecnologias assistivas', 'slug' => 'assistive-technology.index'],
            ['name' => 'Abrir cadastro de tecnologia assistiva', 'slug' => 'assistive-technology.create'],
            ['name' => 'Salvar tecnologias assistivas', 'slug' => 'assistive-technology.store'],
            ['name' => 'Visualizar tecnologias assistivas', 'slug' => 'assistive-technology.show'],
            ['name' => 'Visualizar inspeções de tecnologias assistivas', 'slug' => 'assistive-technology.inspection.show'],
            ['name' => 'Abrir edição de tecnologia assistiva', 'slug' => 'assistive-technology.edit'],
            ['name' => 'Atualizar tecnologias assistivas', 'slug' => 'assistive-technology.update'],
            ['name' => 'Excluir tecnologias assistivas', 'slug' => 'assistive-technology.destroy'],
            ['name' => 'Gerar PDF de tecnologias assistivas', 'slug' => 'assistive-technology.pdf'],
            ['name' => 'Visualizar histórico de tecnologias assistivas', 'slug' => 'assistive-technology.logs'],

            ['name' => 'Listar barreiras', 'slug' => 'barrier.index'],
            ['name' => 'Abrir cadastro de barreira', 'slug' => 'barrier.create'],
            ['name' => 'Salvar barreiras', 'slug' => 'barrier.store'],
            ['name' => 'Visualizar barreiras', 'slug' => 'barrier.show'],
            ['name' => 'Visualizar inspeções de barreiras', 'slug' => 'barrier.inspection.show'],
            ['name' => 'Abrir edição de barreira', 'slug' => 'barrier.edit'],
            ['name' => 'Atualizar barreiras', 'slug' => 'barrier.update'],
            ['name' => 'Excluir barreiras', 'slug' => 'barrier.destroy'],
            ['name' => 'Gerar PDF de barreiras', 'slug' => 'barrier.pdf'],
            ['name' => 'Acessar menu de barreiras', 'slug' => 'barriers.index'],

            ['name' => 'Listar materiais pedagógicos acessíveis', 'slug' => 'material.index'],
            ['name' => 'Abrir cadastro de material pedagógico acessível', 'slug' => 'material.create'],
            ['name' => 'Salvar materiais pedagógicos acessíveis', 'slug' => 'material.store'],
            ['name' => 'Visualizar materiais pedagógicos acessíveis', 'slug' => 'material.show'],
            ['name' => 'Visualizar inspeções de materiais pedagógicos acessíveis', 'slug' => 'material.inspection.show'],
            ['name' => 'Abrir edição de material pedagógico acessível', 'slug' => 'material.edit'],
            ['name' => 'Atualizar materiais pedagógicos acessíveis', 'slug' => 'material.update'],
            ['name' => 'Excluir materiais pedagógicos acessíveis', 'slug' => 'material.destroy'],
            ['name' => 'Gerar PDF de materiais pedagógicos acessíveis', 'slug' => 'material.pdf'],
            ['name' => 'Visualizar histórico de materiais pedagógicos acessíveis', 'slug' => 'material.logs'],

            ['name' => 'Listar eventos institucionais', 'slug' => 'institutional-event.index'],
            ['name' => 'Abrir cadastro de evento institucional', 'slug' => 'institutional-event.create'],
            ['name' => 'Salvar eventos institucionais', 'slug' => 'institutional-event.store'],
            ['name' => 'Visualizar eventos institucionais', 'slug' => 'institutional-event.show'],
            ['name' => 'Abrir edição de evento institucional', 'slug' => 'institutional-event.edit'],
            ['name' => 'Atualizar eventos institucionais', 'slug' => 'institutional-event.update'],
            ['name' => 'Excluir eventos institucionais', 'slug' => 'institutional-event.destroy'],
            ['name' => 'Gerar PDF de eventos institucionais', 'slug' => 'institutional-event.pdf'],

            ['name' => 'Listar empréstimos', 'slug' => 'loan.index'],
            ['name' => 'Abrir cadastro de empréstimo', 'slug' => 'loan.create'],
            ['name' => 'Salvar empréstimos', 'slug' => 'loan.store'],
            ['name' => 'Visualizar empréstimos', 'slug' => 'loan.show'],
            ['name' => 'Abrir edição de empréstimo', 'slug' => 'loan.edit'],
            ['name' => 'Atualizar empréstimos', 'slug' => 'loan.update'],
            ['name' => 'Registrar devolução de empréstimos', 'slug' => 'loan.return'],
            ['name' => 'Excluir empréstimos', 'slug' => 'loan.destroy'],
            ['name' => 'Gerar PDF de empréstimos', 'slug' => 'loan.pdf'],

            ['name' => 'Listar fila de espera', 'slug' => 'waitlist.index'],
            ['name' => 'Abrir cadastro na fila de espera', 'slug' => 'waitlist.create'],
            ['name' => 'Salvar entradas na fila de espera', 'slug' => 'waitlist.store'],
            ['name' => 'Visualizar entradas da fila de espera', 'slug' => 'waitlist.show'],
            ['name' => 'Abrir edição de entrada da fila de espera', 'slug' => 'waitlist.edit'],
            ['name' => 'Atualizar entradas da fila de espera', 'slug' => 'waitlist.update'],
            ['name' => 'Cancelar entradas da fila de espera', 'slug' => 'waitlist.cancel'],
            ['name' => 'Excluir entradas da fila de espera', 'slug' => 'waitlist.destroy'],
            ['name' => 'Gerar PDF da fila de espera', 'slug' => 'waitlist.pdf'],

            // Cadastros administrativos
            ['name' => 'Listar categorias de barreiras', 'slug' => 'barrier-category.index'],
            ['name' => 'Cadastrar categorias de barreiras', 'slug' => 'barrier-category.create'],
            ['name' => 'Salvar categorias de barreiras', 'slug' => 'barrier-category.store'],
            ['name' => 'Visualizar categorias de barreiras', 'slug' => 'barrier-category.show'],
            ['name' => 'Editar categorias de barreiras', 'slug' => 'barrier-category.edit'],
            ['name' => 'Atualizar categorias de barreiras', 'slug' => 'barrier-category.update'],
            ['name' => 'Excluir categorias de barreiras', 'slug' => 'barrier-category.destroy'],
            ['name' => 'Gerenciar categorias de barreiras', 'slug' => 'barrier-category.manage'],

            ['name' => 'Listar instituições', 'slug' => 'institution.index'],
            ['name' => 'Cadastrar instituições', 'slug' => 'institution.create'],
            ['name' => 'Salvar instituições', 'slug' => 'institution.store'],
            ['name' => 'Visualizar instituições', 'slug' => 'institution.show'],
            ['name' => 'Editar instituições', 'slug' => 'institution.edit'],
            ['name' => 'Atualizar instituições', 'slug' => 'institution.update'],
            ['name' => 'Excluir instituições', 'slug' => 'institution.destroy'],
            ['name' => 'Gerenciar instituições', 'slug' => 'institution.manage'],

            ['name' => 'Listar localizações', 'slug' => 'location.index'],
            ['name' => 'Cadastrar localizações', 'slug' => 'location.create'],
            ['name' => 'Salvar localizações', 'slug' => 'location.store'],
            ['name' => 'Visualizar localizações', 'slug' => 'location.show'],
            ['name' => 'Editar localizações', 'slug' => 'location.edit'],
            ['name' => 'Atualizar localizações', 'slug' => 'location.update'],
            ['name' => 'Excluir localizações', 'slug' => 'location.destroy'],
            ['name' => 'Gerenciar localizações', 'slug' => 'location.manage'],

            ['name' => 'Listar recursos de acessibilidade', 'slug' => 'accessibility-feature.index'],
            ['name' => 'Cadastrar recursos de acessibilidade', 'slug' => 'accessibility-feature.create'],
            ['name' => 'Salvar recursos de acessibilidade', 'slug' => 'accessibility-feature.store'],
            ['name' => 'Visualizar recursos de acessibilidade', 'slug' => 'accessibility-feature.show'],
            ['name' => 'Editar recursos de acessibilidade', 'slug' => 'accessibility-feature.edit'],
            ['name' => 'Atualizar recursos de acessibilidade', 'slug' => 'accessibility-feature.update'],
            ['name' => 'Excluir recursos de acessibilidade', 'slug' => 'accessibility-feature.destroy'],
            ['name' => 'Gerenciar recursos de acessibilidade', 'slug' => 'accessibility-feature.manage'],

            // Sistema
            ['name' => 'Acessar painel', 'slug' => 'dashboard.view'],
            ['name' => 'Sair do sistema', 'slug' => 'auth.logout'],
            ['name' => 'Visualizar perfil', 'slug' => 'profile.view'],
            ['name' => 'Atualizar perfil', 'slug' => 'profile.update'],
            ['name' => 'Impersonar usuários', 'slug' => 'admin.impersonate'],
            ['name' => 'Encerrar impersonação', 'slug' => 'admin.impersonate.leave'],

            ['name' => 'Acessar relatórios', 'slug' => 'report.index'],
            ['name' => 'Visualizar dados disponíveis para relatórios', 'slug' => 'report.available-data'],
            ['name' => 'Visualizar metadados de relatórios', 'slug' => 'report.meta'],
            ['name' => 'Executar relatórios', 'slug' => 'report.run'],
            ['name' => 'Exportar relatórios em PDF', 'slug' => 'report.export.pdf'],
            ['name' => 'Acessar relatórios', 'slug' => 'report.reports.index'],
            ['name' => 'Configurar filtros de relatórios', 'slug' => 'report.configure'],

            ['name' => 'Listar cópias de segurança', 'slug' => 'backup.index'],
            ['name' => 'Gerar cópias de segurança', 'slug' => 'backup.store'],
            ['name' => 'Visualizar cópias de segurança', 'slug' => 'backup.show'],
            ['name' => 'Baixar cópias de segurança', 'slug' => 'backup.download'],
            ['name' => 'Enviar cópias de segurança', 'slug' => 'backup.upload'],
            ['name' => 'Restaurar cópias de segurança', 'slug' => 'backup.restore'],
            ['name' => 'Excluir cópias de segurança', 'slug' => 'backup.destroy'],

            ['name' => 'Listar notificações', 'slug' => 'notification.index'],
            ['name' => 'Consultar quantidade de notificações', 'slug' => 'notification.count'],
            ['name' => 'Consultar lista de notificações', 'slug' => 'notification.list'],
            ['name' => 'Marcar notificação como lida', 'slug' => 'notification.read'],
            ['name' => 'Marcar todas as notificações como lidas', 'slug' => 'notification.read-all'],

            ['name' => 'Visualizar auditoria do sistema', 'slug' => 'system.audit.view'],
            ['name' => 'Visualizar administração do sistema', 'slug' => 'system.admin.view'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], ['name' => $p['name']]);
        }

        $basicPermissionIds = Permission::whereIn('slug', [
            'dashboard.view',
            'auth.logout',
            'profile.view',
            'profile.update',
            'notification.index',
            'notification.count',
            'notification.list',
            'notification.read',
            'notification.read-all',
        ])->pluck('id')->toArray();

        Position::query()->each(function (Position $position) use ($basicPermissionIds) {
            $position->permissions()->syncWithoutDetaching($basicPermissionIds);
        });

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

                DB::table('permission_position')->insertOrIgnore($pivotData);
            }
        }
    }
}
