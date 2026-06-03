## 💾 Cópias de Segurança (Backup)

> Gestão das cópias de segurança do sistema, permitindo geração manual, importação, download, visualização, restauração e remoção controlada de arquivos de backup.

---

### 📋 Requisitos Funcionais (RF)

#### **1. Geração e Registro**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-BKP-01` | **Geração Manual:** Permitir que o usuário autenticado gere uma nova cópia de segurança pela tela de gerenciamento de backups. |
| `RF-BKP-02` | **Execução do Backup:** Acionar a rotina `backup:run` com notificações desabilitadas para criar o arquivo compactado do sistema. |
| `RF-BKP-03` | **Registro Persistido:** Após a geração, cadastrar automaticamente o backup na tabela de controle com nome do arquivo, caminho, tamanho, status e usuário responsável. |
| `RF-BKP-04` | **Detecção do Arquivo:** Identificar o arquivo `.zip` mais recente no diretório configurado em `backup.backup.name` para registrar o backup gerado. |

#### **2. Consulta e Filtros**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-BKP-05` | **Listagem Paginada:** Exibir backups em tabela paginada, ordenados do mais recente para o mais antigo. |
| `RF-BKP-06` | **Filtros:** Permitir busca por nome do arquivo e filtro por responsável pelo backup. |
| `RF-BKP-07` | **Atualização Parcial:** Retornar somente a tabela quando a listagem for requisitada via AJAX, mantendo a experiência de filtros dinâmicos. |
| `RF-BKP-08` | **Detalhamento:** Permitir visualizar informações técnicas de um backup específico, incluindo caminho físico, tamanho, status, data de criação e responsável. |

#### **3. Importação e Download**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-BKP-09` | **Importação Manual:** Permitir o envio de arquivo `.zip` externo pela interface administrativa de backups. |
| `RF-BKP-10` | **Validação de Upload:** Exigir arquivo válido, extensão ZIP e tamanho máximo de 100 MB antes de armazenar a importação. |
| `RF-BKP-11` | **Validação de Conteúdo:** Verificar se o ZIP pode ser aberto e se suas entradas são seguras antes de registrar o arquivo importado. |
| `RF-BKP-12` | **Download:** Permitir baixar o arquivo físico do backup quando ele existir no disco local. |
| `RF-BKP-13` | **Erro de Arquivo Ausente:** Informar o usuário quando o registro existir no banco, mas o arquivo físico não estiver disponível no servidor. |

#### **4. Restauração e Remoção**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-BKP-14` | **Confirmação de Restauração:** Exigir confirmação visual antes de restaurar um backup, informando que a ação substitui os dados atuais. |
| `RF-BKP-15` | **Cópia Pré-restauração:** Gerar automaticamente um backup de segurança antes de iniciar qualquer restauração. |
| `RF-BKP-16` | **Modo de Manutenção:** Colocar a aplicação em manutenção durante o processo de restauração e reativá-la ao final do fluxo. |
| `RF-BKP-17` | **Restauração de Dados:** Restaurar o banco de dados a partir do arquivo SQL único encontrado dentro do ZIP validado. |
| `RF-BKP-18` | **Restauração de Arquivos:** Repor arquivos de mídia e anexos quando presentes na estrutura do backup. |
| `RF-BKP-19` | **Exclusão Permanente:** Permitir remover o registro e o arquivo físico do backup após confirmação do usuário. |

#### **5. Sincronização e Automação**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-BKP-20` | **Sincronização de Disco:** Ao acessar a listagem, reconciliar arquivos ZIP existentes no disco com a tabela de backups. |
| `RF-BKP-21` | **Remoção de Registros Órfãos:** Excluir registros de backup cujo arquivo físico não exista mais no armazenamento. |
| `RF-BKP-22` | **Backup Automático:** Disponibilizar comando de console para execução automática da rotina de backup pelo scheduler. |
| `RF-BKP-23` | **Política Visível:** Exibir na interface a política de armazenamento indicando o diretório usado e a regra de retenção manual. |

---

### ⚙️ Requisitos Não Funcionais (RNF)

| Código | Categoria | Descrição |
| --- | --- | --- |
| `RNF-BKP-01` | **Segurança** | A restauração deve aceitar apenas arquivos ZIP localizados dentro do diretório permitido de backups. |
| `RNF-BKP-02` | **Integridade** | O sistema deve rejeitar ZIP com caminhos inseguros, entradas fora do diretório temporário ou estrutura inválida. |
| `RNF-BKP-03` | **Consistência** | Backups importados ou gerados devem manter metadados mínimos: nome, caminho, tamanho, status e responsável. |
| `RNF-BKP-04` | **Recuperação** | A restauração deve criar uma cópia prévia para reduzir risco operacional em caso de falha durante o retorno de dados. |
| `RNF-BKP-05` | **Disponibilidade** | A aplicação deve usar modo de manutenção durante a restauração para evitar alterações concorrentes nos dados. |
| `RNF-BKP-06` | **Observabilidade** | Falhas de geração, upload, sincronização e restauração devem ser registradas em log com mensagem técnica suficiente para diagnóstico. |
| `RNF-BKP-07` | **UX/UI** | Mensagens de sucesso e erro devem ser claras, em **PT-BR**, e explicar o resultado da operação executada. |
| `RNF-BKP-08` | **Infraestrutura** | O ambiente deve possuir ferramentas necessárias para backup e restauração, como `mysqldump`, cliente MySQL e suporte a `ZipArchive`. |

---
