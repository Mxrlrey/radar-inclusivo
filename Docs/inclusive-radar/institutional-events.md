## 📅 Agenda Institucional (InstitutionalEvent)

> Gestão de eventos e compromissos institucionais, com cadastro estruturado, filtros por título e status, exportação em PDF e envio automático de lembretes aos usuários do sistema.

---

### 📋 Requisitos Funcionais (RF)

#### **1. Cadastro e Estrutura**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-EVT-01` | **Estrutura Obrigatória:** O evento deve possuir título, data de início, data de término, horário de início, horário de término e local. |
| `RF-EVT-02` | **Campos Complementares:** Permitir descrição, organizador, público-alvo e indicador de status ativo/inativo. |
| `RF-EVT-03` | **Status de Ativação:** O evento deve poder ser marcado como ativo ou inativo via `is_active`. |

#### **2. Consulta e Gestão**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-EVT-04` | **Listagem Paginada:** Exibir eventos paginados, ordenados por data de início e horário de início. |
| `RF-EVT-05` | **Filtros:** Permitir busca por título e filtro por status ativo. |
| `RF-EVT-06` | **CRUD Completo:** Permitir criação, visualização, edição e exclusão de eventos institucionais. |
| `RF-EVT-07` | **Exportação em PDF:** Permitir gerar uma ficha em PDF de cada evento. |

#### **3. Regras de Datas e Horários**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-EVT-08` | **Período Válido:** A data de término não pode ser anterior à data de início. |
| `RF-EVT-09` | **Horário Consistente:** Quando início e término ocorrerem no mesmo dia, o horário final deve ser maior que o horário inicial. |

#### **4. Lembretes Automáticos**

| Código | Descrição Detalhada |
| --- | --- |
| `RF-EVT-10` | **Lembrete Antecipado:** O sistema deve enviar notificação para usuários quando houver evento marcado para o dia seguinte. |
| `RF-EVT-11` | **Lembrete de Início:** O sistema deve enviar notificação quando o evento estiver iniciando no momento atual. |
| `RF-EVT-12` | **Antiduplicação:** O sistema não deve reenviar lembretes já registrados para o mesmo evento e tipo de notificação. |

---

### ⚙️ Requisitos Não Funcionais (RNF)

| Código | Categoria | Descrição |
| --- | --- | --- |
| `RNF-EVT-01` | **Integridade** | Operações de criação, atualização e exclusão devem ser executadas em **Database Transactions**. |
| `RNF-EVT-02` | **Validação** | Regras de data e horário devem ser validadas tanto na Request quanto na camada de serviço. |
| `RNF-EVT-03` | **Localização** | Todas as mensagens de validação e feedback devem ser apresentadas em **PT-BR**. |
| `RNF-EVT-04` | **Automação** | Os lembretes dependem do scheduler ativo para executar a rotina `inclusive-radar:send-event-reminders`. |
| `RNF-EVT-05` | **Rastreabilidade** | O mecanismo de notificações deve consultar a tabela `notifications` para impedir envios duplicados. |

---
